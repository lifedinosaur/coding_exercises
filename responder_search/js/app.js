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


/**
 * Namespace for our demo application.
 */
var App = (function() {

  /**
   * Results object
   */
  var _results = {};

  /**
   * Retrieves all responders, then updates the table.
   *
   * @param   void
   * @return  void
   */
  function displayResults() {
    $.ajax({
      type: 'GET',
      url: 'responder_search.php',
      dataType: 'json',
      data: {
        'search': $('#search-field').val(),
        'occupation': $('#occupation-filter').val(),
        'availability': $('#availability-filter').val()
      },
      success: function(data) {
        _results = data;
        _redrawTable();
      },
      error: function(XMLHttpRequest, textStatus, errorThrown) {
        alert('Error. Check the console for more details.');
        console.log(XMLHttpRequest);
        console.log(textStatus);
        console.log(errorThrown);
      }
    });
  }

  /**
   * This updates the table, removing old rows and replacing them
   * with the data in _results.
   *
   * @param   void
   * @return  void
   */
  function _redrawTable() {
    if (!_results) { return; }

    var rows = '';
    for (var i in _results) {
      var responder = _results[i];
      rows += '<tr>' +
                '<td>' + responder.name + '</td>' +
                '<td>' + responder.occupation + '</td>' +
                '<td>' + responder.city + ', ' + responder.state + '</td>' +
                '<td>' + responder.status + '</td>' +
              '</tr>';
    }
    
    $('#responder_results tbody').html(rows);
  }

  /**
   * Expose public methods.
   */
  return {
    displayResults: displayResults
  };

})();

$(document).ready(function() {
  App.displayResults();

  $('#search-form').on('submit', function (e) {
    e.preventDefault();
    App.displayResults();
  });

  $('#occupation-filter').on('change', App.displayResults);
  $('#availability-filter').on('change', App.displayResults);
});
