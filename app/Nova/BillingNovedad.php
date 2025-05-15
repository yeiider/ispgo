<?php

namespace App\Nova;

use App\Models\BillingNovedad as Model;
use App\Models\Services\Plan;
use App\Nova\Repeaters\ProductLineItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\{BelongsTo, Boolean, Currency, Date, FormData, ID, Number, Repeater, Select, Textarea};
use Laravel\Nova\Http\Requests\NovaRequest;

class BillingNovedad extends Resource
{
    public static string $model = Model::class;
    public static $title = 'id';
    public static $search = ['id', 'description'];

    public function fields(Request $request): array
    {
        return [
            /* ──────────────── BÁSICOS ──────────────── */
            ID::make()->sortable(),

            BelongsTo::make('Servicio', 'service', Service::class)
                ->searchable()
                ->withoutTrashed()
                ->rules('required'),

            Select::make('Tipo', 'type')
                ->options([
                    Model::T_SALDO_FAVOR => 'Saldo a favor',
                    Model::T_CARGO_ADICIONAL => 'Cargo adicional',
                    Model::T_NOTA_CREDITO => 'Nota de crédito',
                    Model::T_PRORRATEO_INI => 'Prorrateo inicial',
                    Model::T_PRORRATEO_FIN => 'Prorrateo cancelación',
                    Model::T_DESCUENTO_PROMO => 'Descuento promocional',
                    Model::T_CARGO_RECONEXION => 'Cargo reconexión',
                    Model::T_MORA => 'Mora',
                    Model::T_COMPENSACION => 'Compensación',
                    Model::T_EXCESO_CONSUMO => 'Exceso de consumo',
                    Model::T_IMPUESTO => 'Impuesto',
                    Model::T_ENTREGA_PRODUCTO => 'Entrega de producto',
                    Model::T_CAMBIO_PLAN => 'Cambio de plan',
                ])
                ->displayUsingLabels()
                ->rules('required'),

            Currency::make('Monto', 'amount')
                ->currency('COP')
                ->dependsOn(['type'], function ($f, NovaRequest $req, $form) {
                    $editable = in_array($form->type, [
                        Model::T_SALDO_FAVOR,
                        Model::T_CARGO_ADICIONAL,
                        Model::T_NOTA_CREDITO,
                        Model::T_COMPENSACION,
                        Model::T_EXCESO_CONSUMO,
                        Model::T_IMPUESTO,
                    ]);
                    $f->readonly(!$editable);
                    if ($editable) {
                        $f->rules('required', 'numeric', 'min:0.01');
                    }
                })
                ->help('Editable solo en tipos manuales; calculado automáticamente en los demás.'),

            Date::make('Periodo', 'effective_period')
                ->rules('required', 'date')
                ->withMeta([
                    'value' => $this->effective_period
                        ? Carbon::parse($this->effective_period)->format('Y-m-01')
                        : now()->format('Y-m-01'),
                ]),

            Textarea::make('Descripción', 'description')->hideFromIndex(),

            /* ──────────────── CAMPOS PARA PRORRATEO ──────────────── */
            Number::make('Día inicio prorrateo', 'rule->start_day')
                ->dependsOn(['type'], function (Number $f, NovaRequest $r, FormData $d) {
                    if ($d->type === Model::T_PRORRATEO_INI) {
                        $f->show()->rules('required', 'integer', 'between:1,31');
                    } else {
                        $f->hide()->rules([]);
                    }
                })
                ->min(1)->max(31)->step(1)
                ->hideFromIndex(),

            Number::make('Día fin prorrateo', 'rule->end_day')
                ->dependsOn(['type'], function (Number $f, NovaRequest $r, FormData $d) {
                    if ($d->type === Model::T_PRORRATEO_FIN) {
                        $f->show()->rules('required', 'integer', 'between:1,31');
                    } else {
                        $f->hide()->rules([]);
                    }
                })
                ->min(1)->max(31)->step(1)
                ->hideFromIndex(),

            /* 3. Descuento promocional ........................ */
            Number::make('Porcentaje descuento (%)', 'rule->percent')
                ->dependsOn(['type'], function ($f, $r, $d) {
                    if ($d->type === Model::T_DESCUENTO_PROMO) {
                        $f->show()->rules('required,numeric,between:0.01,100');
                    } else {
                        $f->hide()->rules([]);
                    }
                })
                ->step(0.01)->min(0.01)->max(100)
                ->hideFromIndex(),

            Number::make('Ciclos máximos', 'rule->cycles_max')
                ->dependsOn(['type'], function ($f, $r, $d) {
                    if ($d->type === Model::T_DESCUENTO_PROMO) {
                        $f->show()->rules('integer', 'min:1');
                    } else {
                        $f->hide()->rules([]);
                    }
                })
                ->min(1)->step(1)
                ->help('0 = ilimitado')
                ->hideFromIndex(),

            /* 4. Cambio de plan ................................ */
            Select::make('Nuevo plan', 'rule->new_plan_id')
                ->options(fn() => Plan::orderBy('name')->pluck('name', 'id'))
                ->displayUsingLabels()
                ->dependsOn(['type'], function ($f, $r, $d) {
                    if ($d->type === Model::T_CAMBIO_PLAN) {
                        $f->show()->rules('required');
                    } else {
                        $f->hide()->rules([]);
                    }
                })
                ->hideFromIndex(),


            Number::make('Día de cambio', 'rule->change_day')
                ->dependsOn(['type'], function ($f, $r, $d) {
                    if ($d->type === Model::T_CAMBIO_PLAN) {
                        $f->show()->rules('required', 'integer', 'between:1,31');
                    } else {
                        $f->hide()->rules([]);
                    }
                })
                ->min(1)->max(31)->step(1)
                ->hideFromIndex(),

            /* 5. Cargo Reconexión ................................ */
            Select::make('Tipo de cargo', 'rule->charge_type')
                ->options([
                    'fixed' => 'Monto fijo',
                    'percentage' => 'Porcentaje del plan',
                ])
                ->displayUsingLabels()
                ->dependsOn(['type'], function ($f, $r, $d) {
                    if ($d->type === Model::T_CARGO_RECONEXION) {
                        $f->show()->rules('required');
                    } else {
                        $f->hide()->rules([]);
                    }
                })
                ->hideFromIndex(),

            Number::make('Valor del cargo', 'rule->charge_value')
                ->dependsOn(['type'], function ($f, $r, $d) {
                    if ($d->type === Model::T_CARGO_RECONEXION) {
                        $f->show()->rules('required', 'numeric', 'min:0.01');
                    } else {
                        $f->hide()->rules([]);
                    }
                })
                ->step(0.01)->min(0.01)
                ->hideFromIndex(),

            /* 6. Mora ................................ */
            Select::make('Tipo de mora', 'rule->mora_type')
                ->options([
                    'fixed' => 'Monto fijo',
                    'percentage' => 'Porcentaje del saldo',
                    'daily_interest' => 'Interés diario',
                ])
                ->displayUsingLabels()
                ->dependsOn(['type'], function ($f, $r, $d) {
                    if ($d->type === Model::T_MORA) {
                        $f->show()->rules('required');
                    } else {
                        $f->hide()->rules([]);
                    }
                })
                ->hideFromIndex(),

            Number::make('Valor de mora', 'rule->mora_value')
                ->dependsOn(['type'], function ($f, $r, $d) {
                    if ($d->type === Model::T_MORA) {
                        $f->show()->rules('required', 'numeric', 'min:0.01');
                    } else {
                        $f->hide()->rules([]);
                    }
                })
                ->step(0.01)->min(0.01)
                ->hideFromIndex(),

            Currency::make('Monto pendiente', 'rule->pending_amount')
                ->currency('COP')
                ->dependsOn(['type'], function ($f, $r, $d) {
                    if ($d->type === Model::T_MORA) {
                        $f->show()->rules('required', 'numeric', 'min:0.01');
                    } else {
                        $f->hide()->rules([]);
                    }
                })
                ->hideFromIndex(),

            Date::make('Fecha vencimiento', 'rule->due_date')
                ->dependsOn(['type', 'rule->mora_type'], function ($f, $r, $d) {
                    if ($d->type === Model::T_MORA && $d->{'rule->mora_type'} === 'daily_interest') {
                        $f->show()->rules('required', 'date');
                    } else {
                        $f->hide()->rules([]);
                    }
                })
                ->hideFromIndex(),

            Currency::make('Monto máximo', 'rule->max_amount')
                ->currency('COP')
                ->dependsOn(['type'], function ($f, $r, $d) {
                    if ($d->type === Model::T_MORA) {
                        $f->show();
                    } else {
                        $f->hide()->rules([]);
                    }
                })
                ->hideFromIndex(),

            /* 7. Nota de Crédito ................................ */
            Currency::make('Monto de crédito', 'rule->credit_amount')
                ->currency('COP')
                ->dependsOn(['type'], function ($f, $r, $d) {
                    if ($d->type === Model::T_NOTA_CREDITO) {
                        $f->show()->rules('required', 'numeric', 'min:0.01');
                    } else {
                        $f->hide()->rules([]);
                    }
                })
                ->hideFromIndex(),

            Boolean::make('Limitar al precio mensual', 'rule->limit_to_monthly_price')
                ->dependsOn(['type'], function ($f, $r, $d) {
                    if ($d->type === Model::T_NOTA_CREDITO) {
                        $f->show();
                    } else {
                        $f->hide()->rules([]);
                    }
                })
                ->hideFromIndex(),

            Number::make('Porcentaje máximo (%)', 'rule->max_percentage')
                ->dependsOn(['type'], function ($f, $r, $d) {
                    if ($d->type === Model::T_NOTA_CREDITO) {
                        $f->show();
                    } else {
                        $f->hide()->rules([]);
                    }
                })
                ->step(0.01)->min(0.01)->max(100)
                ->hideFromIndex(),

            /* 8. Compensación ................................ */
            Select::make('Tipo de compensación', 'rule->compensation_type')
                ->options([
                    'fixed' => 'Monto fijo',
                    'percentage' => 'Porcentaje del plan',
                    'days' => 'Por días sin servicio',
                ])
                ->displayUsingLabels()
                ->dependsOn(['type'], function ($f, $r, $d) {
                    if ($d->type === Model::T_COMPENSACION) {
                        $f->show()->rules('required');
                    } else {
                        $f->hide()->rules([]);
                    }
                })
                ->hideFromIndex(),

            Number::make('Valor de compensación', 'rule->compensation_value')
                ->dependsOn(['type'], function ($f, $r, $d) {
                    if ($d->type === Model::T_COMPENSACION) {
                        $f->show()->rules('required', 'numeric', 'min:0.01');
                    } else {
                        $f->hide()->rules([]);
                    }
                })
                ->step(0.01)->min(0.01)
                ->hideFromIndex(),

            Date::make('Inicio de falla', 'rule->outage_start')
                ->dependsOn(['type', 'rule->compensation_type'], function ($f, $r, $d) {
                    if ($d->type === Model::T_COMPENSACION && $d->{'rule->compensation_type'} === 'days') {
                        $f->show()->rules('required', 'date');
                    } else {
                        $f->hide()->rules([]);
                    }
                })
                ->hideFromIndex(),

            Date::make('Fin de falla', 'rule->outage_end')
                ->dependsOn(['type', 'rule->compensation_type'], function ($f, $r, $d) {
                    if ($d->type === Model::T_COMPENSACION && $d->{'rule->compensation_type'} === 'days') {
                        $f->show()->rules('required', 'date');
                    } else {
                        $f->hide()->rules([]);
                    }
                })
                ->hideFromIndex(),

            Number::make('Factor de compensación', 'rule->compensation_factor')
                ->dependsOn(['type'], function ($f, $r, $d) {
                    if ($d->type === Model::T_COMPENSACION) {
                        $f->show();
                    } else {
                        $f->hide()->rules([]);
                    }
                })
                ->step(0.01)->min(0.01)
                ->help('Factor multiplicador opcional (ej: 1.5 = 150% de compensación)')
                ->hideFromIndex(),

            /* 9. Exceso de Consumo ................................ */
            Number::make('Datos usados (GB)', 'rule->data_used')
                ->dependsOn(['type'], function ($f, $r, $d) {
                    if ($d->type === Model::T_EXCESO_CONSUMO) {
                        $f->show()->rules('required', 'numeric', 'min:0.01');
                    } else {
                        $f->hide()->rules([]);
                    }
                })
                ->step(0.01)->min(0.01)
                ->hideFromIndex(),

            Select::make('Tipo de cargo por exceso', 'rule->charge_type')
                ->options([
                    'per_unit' => 'Por unidad (GB)',
                    'flat' => 'Tarifa plana',
                    'tiered' => 'Por niveles',
                ])
                ->displayUsingLabels()
                ->dependsOn(['type'], function ($f, $r, $d) {
                    if ($d->type === Model::T_EXCESO_CONSUMO) {
                        $f->show()->rules('required');
                    } else {
                        $f->hide()->rules([]);
                    }
                })
                ->hideFromIndex(),

            Currency::make('Tarifa por unidad', 'rule->unit_rate')
                ->currency('COP')
                ->dependsOn(['type', 'rule->charge_type'], function ($f, $r, $d) {
                    if ($d->type === Model::T_EXCESO_CONSUMO && $d->{'rule->charge_type'} === 'per_unit') {
                        $f->show()->rules('required', 'numeric', 'min:0.01');
                    } else {
                        $f->hide()->rules([]);
                    }
                })
                ->hideFromIndex(),

            Currency::make('Tarifa plana', 'rule->flat_fee')
                ->currency('COP')
                ->dependsOn(['type', 'rule->charge_type'], function ($f, $r, $d) {
                    if ($d->type === Model::T_EXCESO_CONSUMO && $d->{'rule->charge_type'} === 'flat') {
                        $f->show()->rules('required', 'numeric', 'min:0.01');
                    } else {
                        $f->hide()->rules([]);
                    }
                })
                ->hideFromIndex(),

            Currency::make('Cargo máximo', 'rule->max_charge')
                ->currency('COP')
                ->dependsOn(['type'], function ($f, $r, $d) {
                    if ($d->type === Model::T_EXCESO_CONSUMO) {
                        $f->show();
                    } else {
                        $f->hide()->rules([]);
                    }
                })
                ->hideFromIndex(),

            /* 10. Impuesto ................................ */
            Select::make('Tipo de impuesto', 'rule->tax_type')
                ->options([
                    'percentage' => 'Porcentaje',
                    'fixed' => 'Monto fijo',
                ])
                ->displayUsingLabels()
                ->dependsOn(['type'], function ($f, $r, $d) {
                    if ($d->type === Model::T_IMPUESTO) {
                        $f->show()->rules('required');
                    } else {
                        $f->hide()->rules([]);
                    }
                })
                ->hideFromIndex(),

            Number::make('Valor del impuesto', 'rule->tax_value')
                ->dependsOn(['type'], function ($f, $r, $d) {
                    if ($d->type === Model::T_IMPUESTO) {
                        $f->show()->rules('required', 'numeric', 'min:0.01');
                    } else {
                        $f->hide()->rules([]);
                    }
                })
                ->step(0.01)->min(0.01)
                ->hideFromIndex(),

            Currency::make('Base imponible', 'rule->taxable_amount')
                ->currency('COP')
                ->dependsOn(['type'], function ($f, $r, $d) {
                    if ($d->type === Model::T_IMPUESTO) {
                        $f->show();
                    } else {
                        $f->hide()->rules([]);
                    }
                })
                ->help('Si se deja vacío, se usará el precio mensual del plan')
                ->hideFromIndex(),

            Select::make('Modo de redondeo', 'rule->rounding_mode')
                ->options([
                    'up' => 'Hacia arriba',
                    'down' => 'Hacia abajo',
                    'nearest' => 'Al más cercano',
                ])
                ->displayUsingLabels()
                ->dependsOn(['type'], function ($f, $r, $d) {
                    if ($d->type === Model::T_IMPUESTO) {
                        $f->show();
                    } else {
                        $f->hide()->rules([]);
                    }
                })
                ->hideFromIndex(),

            Number::make('Decimales', 'rule->decimals')
                ->dependsOn(['type'], function ($f, $r, $d) {
                    if ($d->type === Model::T_IMPUESTO) {
                        $f->show();
                    } else {
                        $f->hide()->rules([]);
                    }
                })
                ->min(0)->max(6)->step(1)->default(2)
                ->hideFromIndex(),

            /* ──────────────── REPEATER (sin dependsOn) ──────────────── */
            Repeater::make('Productos', 'product_lines')
                ->repeatables([ProductLineItem::make()])
                ->asJson()
                ->help('Solo se usa en "Entrega de producto".'),

            /* ──────────────── ESTADO LECTURA ──────────────── */
            Boolean::make('Aplicada', 'applied')
                ->exceptOnForms(),
        ];
    }

    /** Garantiza que rule sea array antes de validar */
    public static function beforeValidation(NovaRequest $request, $model)
    {
        $model->rule = $model->rule ?? [];
    }
}
