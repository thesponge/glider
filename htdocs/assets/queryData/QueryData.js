/*

QueryData.js

A function to parse data from a query string

Created by Stephen Morley - http://code.stephenmorley.org/ - and released under
the terms of the CC0 1.0 Universal legal code:

http://creativecommons.org/publicdomain/zero/1.0/legalcode

*/

/* Creates an object containing data parsed from the specified query string. The
 * parameters are:
 *
 * queryString        - the query string to parse. The query string may start
 *                      with a question mark, spaces may be encoded either as
 *                      plus signs or the escape sequence '%20', and both
 *                      ampersands and semicolons are permitted as separators.
 *                      This optional parameter defaults to query string from
 *                      the page URL.
 */
function QueryData(queryString){

  // if a query string wasn't specified, use the query string from the URL
  if (queryString == undefined){
    queryString = location.search ? location.search : '';
  }

  // remove the leading question mark from the query string if it is present
  if (queryString.charAt(0) == '?') queryString = queryString.substring(1);

  // check whether the query string is empty
  if (queryString.length > 0){

    // replace plus signs in the query string with spaces
    queryString = queryString.replace(/\+/g, ' ');

    // split the query string around ampersands and semicolons
    var queryComponents = queryString.split(/[&;]/g);

    // loop over the query string components
    for (var index = 0; index < queryComponents.length; index ++){

      // extract this component's key-value pair
      var keyValuePair = queryComponents[index].split('=');
      var key          = decodeURIComponent(keyValuePair[0]);
      var value        = keyValuePair.length > 1
                       ? decodeURIComponent(keyValuePair[1])
                       : '';


      // altered by Ioana
      if(typeof this[key] == 'undefined') {
        // store the value
        this[key] = value;

      } else if(typeof this[key] == 'object') {
          // daca este obiect => este array
          this[key].push(value);

      } else {
          // daca nu este undefined si nu este inca obiect
          var man = this[key];
          delete this[key];
          this[key] = [];
          this[key].push(man);
          this[key].push(value);
      }

    }

  }

}
