<?php

namespace App\Providers;

use Form;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Form::component('bsText', 'components.form.text', ['name', 'label' => null, 'value' => null, 'attributes' => []]);
        Form::component('bsFile', 'components.form.file', ['name', 'label' => null, 'attributes' => []]);
        Form::component('bsTextarea', 'components.form.textarea', ['name', 'label' => null, 'value' => null, 'attributes' => []]);
        Form::component('bsPassword', 'components.form.password', ['name', 'label' => null, 'attributes' => []]);
        Form::component('bsDate', 'components.form.date', ['name', 'label' => null, 'value' => null, 'attributes' => []]);
        Form::component('bsTime', 'components.form.time', ['name', 'label' => null, 'value' => null, 'attributes' => []]);
        Form::component('bsSelect', 'components.form.select', ['name', 'label' => null, 'options' => [], 'value' => null, 'attributes' => []]);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
