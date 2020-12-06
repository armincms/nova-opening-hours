<?php

namespace Armincms\Fields\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Str;

class Overlap implements Rule
{ 
    /**
     * The overlapped day.
     * 
     * @var string|null
     */
    public $overlapping = null;

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    { 
        return ! $this->hasOverlap(json_decode($value, true));
    } 

    /**
     * Check if the given days has overlapping.
     * 
     * @param  array $days
     * @return boolean       
     */
    public function hasOverlap(array $days)
    {
        return collect($days)->first(function($data, $day) {
            $this->overlapping = $day;

            return $this->removeHourOverlapped(collect($data)->pluck('hours')->all())->count() !== count($data); 
        });
    } 

    /**
     * Remove overlapping hour from given hours.
     * 
     * @param  array $days
     * @return \Illuminate\Support\Collection       
     */
    public function removeHourOverlapped(array $hours)
    {
        return collect($hours)->reject(function($hour, $key) use ($hours) {
            return collect($hours)->except($key)->filter(function($target) use ($hour) { 
                return Str::after($hour, '-') > Str::before($target, '-') &&
                       Str::before($hour, '-') <  Str::after($target, '-'); 
            })->count();
        });
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('The :day hours overlap.', [
            'day' => Str::studly($this->overlapping)
        ]);
    }
}
