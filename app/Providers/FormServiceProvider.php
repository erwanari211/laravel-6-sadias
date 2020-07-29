<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Form;

class FormServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->addCustomFormComponents();
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    public function addCustomFormComponents()
    {
        $style = config('my_app.form_component.style');

        if ($style == 'bootstrap-3') {
            $this->boostrap3Components();
        }

        if ($style == 'bootstrap-4') {
            $this->boostrap4Components();
        }
    }

    public function boostrap3Components()
    {
        Form::component('bsText', 'components.forms.bs3.text', ['name', 'value' => null, 'attributes' => []]);
        Form::component('bsTextarea', 'components.forms.bs3.textarea', ['name', 'value' => null, 'attributes' => []]);
        Form::component('bsPassword', 'components.forms.bs3.password', ['name', 'attributes' => []]);
        Form::component('bsEmail', 'components.forms.bs3.email', ['name', 'value' => null, 'attributes' => []]);
        Form::component('bsFile', 'components.forms.bs3.file', ['name', 'attributes' => []]);
        Form::component('bsNumber', 'components.forms.bs3.number', ['name', 'value' => null, 'attributes' => []]);
        Form::component('bsDate', 'components.forms.bs3.date', ['name', 'value' => null, 'attributes' => []]);
        Form::component('bsSelect', 'components.forms.bs3.select', ['name', 'options'=>[], 'value' => null, 'attributes' => []]);
        Form::component('bsSubmit', 'components.forms.bs3.submit', ['name', 'attributes' => []]);
        Form::component('bsLinkToRoute', 'components.forms.bs3.link-to-route', ['routeName', 'title'=>null, 'parameters'=>[], 'attributes' => []]);
        Form::component('bsDatetimepicker', 'components.forms.bs3.datetimepicker', ['name', 'value' => null, 'attributes' => []]);
        Form::component('bsDatepicker', 'components.forms.bs3.datepicker', ['name', 'value' => null, 'attributes' => []]);
        Form::component('bsTimepicker', 'components.forms.bs3.timepicker', ['name', 'value' => null, 'attributes' => []]);
        Form::component('bsLinkToRouteHtml', 'components.forms.bs3.link-to-route-html', ['routeName', 'title'=>null, 'parameters'=>[], 'attributes' => []]);
        Form::component('bsSubmitHtml', 'components.forms.bs3.submit-html', ['name', 'attributes' => []]);
    }

    public function boostrap4Components()
    {
        Form::component('bsText', 'components.forms.bs4.text', ['name', 'value' => null, 'attributes' => []]);
        Form::component('bsTextarea', 'components.forms.bs4.textarea', ['name', 'value' => null, 'attributes' => []]);
        Form::component('bsPassword', 'components.forms.bs4.password', ['name', 'attributes' => []]);
        Form::component('bsEmail', 'components.forms.bs4.email', ['name', 'value' => null, 'attributes' => []]);
        Form::component('bsFile', 'components.forms.bs4.file', ['name', 'attributes' => []]);
        Form::component('bsNumber', 'components.forms.bs4.number', ['name', 'value' => null, 'attributes' => []]);
        Form::component('bsDate', 'components.forms.bs4.date', ['name', 'value' => null, 'attributes' => []]);
        Form::component('bsSelect', 'components.forms.bs4.select', ['name', 'options'=>[], 'value' => null, 'attributes' => []]);
        Form::component('bsSubmit', 'components.forms.bs4.submit', ['name', 'attributes' => []]);
        Form::component('bsLinkToRoute', 'components.forms.bs4.link-to-route', ['routeName', 'title'=>null, 'parameters'=>[], 'attributes' => []]);
        Form::component('bsDatetimepicker', 'components.forms.bs4.datetimepicker', ['name', 'value' => null, 'attributes' => []]);
        Form::component('bsDatepicker', 'components.forms.bs4.datepicker', ['name', 'value' => null, 'attributes' => []]);
        Form::component('bsTimepicker', 'components.forms.bs4.timepicker', ['name', 'value' => null, 'attributes' => []]);
        Form::component('bsLinkToRouteHtml', 'components.forms.bs4.link-to-route-html', ['routeName', 'title'=>null, 'parameters'=>[], 'attributes' => []]);
        Form::component('bsSubmitHtml', 'components.forms.bs4.submit-html', ['name', 'attributes' => []]);
    }
}
