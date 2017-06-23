<?php
/**
 * Copyright 2014 Intermedix Corporation.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing,
 * software distributed under the License is distributed on an "AS
 * IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either
 * express or implied.  See the License for the specific language
 * governing permissions and limitations under the License.
 */

class responder_search {

  /**
   * Checks the given 'availability' value is not default and is present 
   * on the given object.
   * 
   * @param  string  $availability The availability/status value.
   * @param  array   $resp         The responder array to check.
   * @return boolean The result of the search.
   */
  private static function check_availability ($availability, $resp) {
    return ($availability === 'All' || $resp['status'] === $availability);
  }

  /**
   * Checks the given 'occupation' value is not default and is present 
   * on the given object.
   * 
   * @param  string  $occupation The occupation value.
   * @param  array   $resp       The responder array to check.
   * @return boolean The result of the search.
   */
  private static function check_occupation ($occupation, $resp) {
    return ($occupation === 'All' || $resp['occupation'] === $occupation);
  }

  /**
   * Checks the given search term is not default and is present on the 
   * given object.
   * 
   * @param  string  $search The search term.
   * @param  array   $resp   The responder array to check.
   * @return boolean The result of the search.
   */
  private static function check_search ($search, $resp) {
    return (
      $search === '' ||
      self::search_array($search, $resp, ['occupation', 'status'])
    );
  }

  /**
   * Returns a hard-coded array of responder data.
   * 
   * @param   string $search       Search term from the "search" field.
   * @param   string $occupation   Filter from the "occupation" dropdown.
   * @param   string $availability Filter from the "availability" dropdown.
   * @return  array  The responder's name, occupation, location, and status.
   */
  public static function get_results ($search, $occupation, $availability) {
    // Hard-coded data source:
    $table = array(
       array('name' => 'Christopher Baker',
             'occupation' => 'Respiratory Therapist',
             'city' => 'Pittsburgh',
             'state' => 'PA',
             'status' => 'Available'
      ),
       array('name' => 'Elizabeth Brown',
             'occupation' => 'Veterinarian',
             'city' => 'Fort Lauderdale',
             'state' => 'FL',
             'status' => 'Unknown'
      ),
       array('name' => 'Nathan Allan',
             'occupation' => 'Physician',
             'city' => 'Pittsburgh',
             'state' => 'PA',
             'status' => 'Not Available'
      ),
      array('name' => 'Wendy Campbell',
            'occupation' => 'Veterinarian',
            'city' => 'Milwaukee',
            'state' => 'WI',
            'status' => 'Available'
      )
    );

    // Return entire data-set if any values are default:
    if ($search === '' && $occupation === 'All' && $availability === 'All') {
      return $table;
    }
    
    // Perform filter:
    $results = array();

    // For each value in the data-set, check occupation, availability/status,
    // and search term filters for inclusion in the filtered set:
    foreach ($table as $resp) {
      if (
        self::check_occupation($occupation, $resp) &&
        self::check_availability($availability, $resp) &&
        self::check_search($search, $resp)
      ) {
        array_push($results, $resp);
      }
    }

    return $results;
  }

  /**
   * Checks if a partial string match exists within an array, for each key 
   * except those specifically excluded.
   * 
   * @param  string  $search  String to find within the array.
   * @param  array   $array   The keyed array to search.
   * @param  array   $exclude Optional array of keys to exclude from the search.
   * @return boolean The result of the search.
   */
  private static function search_array ($search, $array, $exclude = []) {
    foreach($array as $key => $value) {
      // match string insensitive if key is not excluded:
      if (stristr($value, $search) && !in_array($key, $exclude)) {
        return true;
      }
    }
    return false;
  }
}

$results = responder_search::get_results(
  trim($_GET['search']),  // remove whitespace from search term field
  $_GET['occupation'],
  $_GET['availability']
);

echo json_encode($results);

?>
