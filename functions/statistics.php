<?php
/**
 * Class Zume_Statistics
 */
class Zume_Statistics
{
    /** Singleton @var null */
    private static $_instance = null;

    public static function instance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Constructor function.
     *
     * @access  public
     * @since   0.1.0
     */
    public function __construct()
    {
        add_action( 'rest_api_init', array( $this,  'add_api_routes' ) );
    } // End __construct()

    public function add_api_routes()
    {
        $namespace_v1 = 'grid/v1';

        register_rest_route( $namespace_v1, '/population', array(
            array(
                'methods' => WP_REST_Server::CREATABLE,
                'callback' => array($this, 'population'),
            ),
        ));
    }

    public function population( WP_REST_Request $request){
        $params = $request->get_json_params();
        if ( isset( $params['grid_id'] ) ){

            return true;

        } else {
            return new WP_Error( "tract_param_error", "Please provide a valid address", array( 'status' => 400 ) );
        }
    }

    public function statistics() : array {

        // world variables
        $world_population = 7543334085; // world population estimate at Jan 1, 2019 @link https://www.census.gov/newsroom/press-releases/2019/new-years-population.html
        $world_population_timestamp = 1546300800; // unix seconds at Jan 1, 2019
        $current_timestamp = time(); // unix time stamp for right now
        $births_per_second = 4.3;
        $deaths_per_second = 1.8;
        $non_christian_population = 68.8;
        $christless_deaths_per_second = 1.2384; // 68.8 percent of the population is not Christian
        $births_millisecond_interval = 1000 / $births_per_second;
        $deaths_millisecond_interval = 1000 / $deaths_per_second;
        $christless_deaths_millisecond_interval = 1000 / $christless_deaths_per_second;

        $seconds_since_world_population_timestamp = $current_timestamp - $world_population_timestamp;

        $calculated_population = ceil( ( $births_per_second * $seconds_since_world_population_timestamp ) + $world_population - ( $deaths_per_second * $seconds_since_world_population_timestamp ) );
        $trainings_per_population = ceil( $calculated_population / 5000 );
        $churches_per_population = ceil( $calculated_population / 2500 );

        // today
        $seconds_since_midnight = $current_timestamp - strtotime('midnight');
        $births_today = ceil( $births_per_second * $seconds_since_midnight );
        $deaths_today = ceil( $deaths_per_second * $seconds_since_midnight );
        $christless_deaths_today = ceil( $christless_deaths_per_second * $seconds_since_midnight );

        $calculated_population_today = ceil( $births_today - $deaths_today );
        $deaths_without_christ_today = ceil($calculated_population_today / $non_christian_population );


        return [
            'time' => time(),
            'counter' => [
                1 => [
                    'calculated_population_year' => $calculated_population,
                    'calculated_population_today' => $calculated_population_today,
                    'births_today' => $births_today,
                    'deaths_today' => $deaths_today,
                    'christless_deaths_today' => $christless_deaths_today,
                    'christless_deaths_interval' => $christless_deaths_millisecond_interval,
                    'births_interval' => $births_millisecond_interval,
                    'deaths_interval' => $deaths_millisecond_interval,
                    'trainings_needed' => $trainings_per_population,
                    'churches_needed' => $churches_per_population,
                    'trainings_reported' => 222,
                    'churches_reported' => 8,
                ]
            ]
        ];
    }

    /**
     * Sources
     * @link https://data.worldbank.org/indicator/SP.DYN.CDRT.IN (death)
     * @link https://data.worldbank.org/indicator/SP.DYN.CBRT.IN (birth)
     * @link https://www.rapidtables.com/calc/time/seconds-in-year.html ( seconds in a year 31556952 )
     * @link https://www.census.gov/popclock/
     */

}
Zume_Statistics::instance();
