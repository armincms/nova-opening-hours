# nova-opening-hours
A Nova field for the Spatie opening hours.


## Installation
    composer require armincms/nova-opening-hours
    
## Usage

At the first, In the Laravel model should cast the attribute to the Array then In the Nova resources follow the  below:
 
    use Armincms\Fields\OpeningHours;

    ...
    OpeningHours::make('Name')
    ...
# Restriction
You can use the `restrictTo` to restrict the user to enter specific hours,  like the below:

    OpeningHours::make(__('Opening Hours'), '_sfore_opening_hours_')
                ->restrictTo([ 
                  'first restriction' => 'Label',
                  'second restriction' => 'Label', 
                  ...
                ]) 

This restriction will apply to all weekdays. if you want to restrict some days use the following:

    OpeningHours::make(__('Opening Hours'), '_sfore_opening_hours_')
                ->restrictionOnSunday([ 
                  'first restriction' => 'Label',
                  'second restriction' => 'Label', 
                  ...
                ]) 
                ->restrictionOnWednesday([ 
                  'first restriction' => 'Label',
                  'second restriction' => 'Label', 
                  ...
                ]) 

# Storing
This package stores the opening hours in the following format:

    $openingHours = [
        'sunday' => [
          [
            'data' => 'your data',
            'hours' => 'your hours', // 00:08-10:00
          ],
          [ 
            'data' => 'your second data',
            'hours' => 'your second hours', // 11:00-20:00
          ]
        ],
        
        'friday' => [
          [
            'data' => 'your data',
            'hours' => 'your hours', // 00:08-10:00
          ],
          [ 
            'data' => 'your second data',
            'hours' => 'your second hours', // 11:00-20:00
          ]
        ],
    ];
