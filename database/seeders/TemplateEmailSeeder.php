<?php

namespace Database\Seeders;

use App\Models\EmailTemplate;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TemplateEmailSeeder extends Seeder
{
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        EmailTemplate::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        EmailTemplate::create([
            'name' => 'Header Template',
            'subject' => '',
            'body' => <<<HTML
   <div class="raw-html-embed"><div class="header"><img src="{{ asset('img/logo.png') }}" alt="Logo"></div></div>'
HTML,
            'styles' => '',
            'entity' => null,
            'is_active' => true,
            'created_by' => 1,
            'updated_by' => 1,
            'test_email' => null,
            'description' => 'Template for email header'
        ]);
        EmailTemplate::create([
            'name' => 'Footer Template',
            'subject' => '',
            'body' => '<div class="footer"><p>&copy; {{ date(\'Y\') }} Your Company. All rights reserved.</p></div>',
            'styles' => '',
            'entity' => null,
            'is_active' => true,
            'created_by' => 1,
            'updated_by' => 1,
            'test_email' => null,
            'description' => 'Template for email footer'
        ]);

        EmailTemplate::create([
            'name' => 'Welcome Email',
            'subject' => 'Bienvenido, {{ customer.full_name }}',
            'body' => <<<HTML
            <div class="raw-html-embed">{{ template template_id=1 }} <!-- Header Template --> <h2>Hola {{ first_name }},</h2> <p>{{ trans "welcome " }}{{ full_name }}</p> <p>Tu correo registrado es: {{ email_address }}</p> <div style="text-align: center; margin: 20px 0;"> <a href="{{ url('shop') }}" class="button">Ir de compras</a> </div> <!-- Footer Template --></div>
    HTML,
            'entity' => 'customer',
            'is_active' => true,
            'created_by' => 1,
            'updated_by' => 1,
            'test_email' => null,
            'description' => 'Template for welcome email'
        ]);


    }
}