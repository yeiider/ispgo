<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Resource;

class Zone extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Zone::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        // Generar opciones para los días del mes del 1 al 30
        $daysOfMonth = [];
        for ($day = 1; $day <= 30; $day++) {
            $daysOfMonth[$day] = __('Day ') . $day . __(' of each month');
        }

        return [
            ID::make(__('ID'), 'id')->sortable(),

            Select::make(__('Type'), 'type')->options([
                'prepaid' => __('Prepaid'),
                'postpaid' => __('Postpaid'),
            ])->sortable(),

            Select::make(__('Invoice Notice Type'), 'invoice_notice_type')->options([
                'email' => __('Email'),
                'sms' => __('SMS'),
                'notification' => __('Notification'),
            ])->sortable(),

            Select::make(__('Create Invoice Day'), 'create_invoice_day')->options($daysOfMonth)->sortable(),

            DateTime::make(__('Create Invoice Time'), 'create_invoice_time')->sortable(),

            Select::make(__('Payment Day'), 'payment_day')->options($daysOfMonth)->sortable(),

            Select::make(__('Payment Reminder'), 'payment_reminder')->options($daysOfMonth)->sortable(),

            Boolean::make(__('Send Push Notifications'), 'send_push_notifications'),

            Select::make(__('Cutoff Day'), 'cutoff_day')->options($daysOfMonth)->sortable(),

            Select::make(__('Suspend Service'), 'suspend_service')->options([
                '1' => __('1 Invoice Overdue'),
                '2' => __('2 Invoices Overdue'),
            ])->sortable(),


            Number::make(__('Taxes'), 'taxes')->min(0)->max(100)->step(0.01),

            Boolean::make(__('Automatic Cut'), 'automatic_cut'),
            Boolean::make(__('Automatic Invoice'), 'automatic_invoice'),
            Boolean::make(__('Screen Notice'), 'screen_notice'),
            Boolean::make(__('Payment Reminder Notice'), 'payment_reminder_notice'),
            Boolean::make(__('Receive Cut Email'), 'receive_cut_email'),
            Boolean::make(__('Receive Invoice Email'), 'receive_invoice_email'),
            Boolean::make(__('Receive Notice Email'), 'receive_notice_email'),
        ];
    }
    // Other methods...
}
