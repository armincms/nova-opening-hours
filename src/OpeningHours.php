<?php

namespace Armincms\Fields;

use Illuminate\Support\Str;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\Field;
use Carbon\Carbon;

class OpeningHours extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'nova-opening-hours';

    /**
     * Indicates if the element should be shown on the index view.
     *
     * @var \Closure|bool
     */
    public $showOnIndex = false;

    /**
     * Hydrate the given attribute on the model based on the incoming request.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  string  $requestAttribute
     * @param  object  $model
     * @param  string  $attribute
     * @return mixed
     */
    protected function fillAttributeFromRequest(NovaRequest $request, $requestAttribute, $model, $attribute)
    {
        if ($request->exists($requestAttribute)) {
            $value = json_decode($request[$requestAttribute], true);

            $model->{$attribute} = $this->isNullValue($value) ? null : $value;
        }
    }

    /**
     * Restrict the day hours with the given hours.
     * 
     * @param  array|string $hours 
     * @param  array|string $days  
     * @return this        
     */
    public function restrictTo($hours, array $days = [])
    {    
        $hours = is_array($hours) ? $hours : (array) $hours;

        collect($days ?: $this->getDays())->each(function($day) use ($hours) { 
            if(array_keys($hours) == range(0, count($hours))) {
                $hours = array_combine($hours, $hours);
            }

            $this->withMeta([Str::lower($day) => $hours]);
        });

        return $this;
    }

    /**
     * Get the days of the week
     *
     * @return array
     */
    public function getDays()
    { 
        return collect(Carbon::getDays())->map(function($day) {
            return Str::lower($day);
        });
    }

    /**
     * Get the validation rules for this field.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function getRules(NovaRequest $request)
    {
        return array_merge_recursive(parent::getRules($request), [
            $this->attribute => [new Rules\Overlap],
        ]);
    }

    /**
     * Prepare the field for JSON serialization.
     *
     * @return array
     */
    public function jsonSerialize()
    { 
        return array_merge(parent::jsonSerialize(), [
            'days'  => $this->getDays(), 
            'today' => Str::lower(now()->format('l')),
        ]);
    }

    /**
     * Dynamically call the days restrictions.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        if($day = Str::after($method, 'restrictionOn')) {  
            return $this->restrictTo($parameters[0], compact('day'));
        }

        return parent::__call($method, $parameters); 
    }
}
