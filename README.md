# Querying DB
#### Note: Not complete - work in progress

## Use with Javascript
1. Insert `<script src="js/jquery-3.2.1.min.js"></script>` (for easy ajax calls) and `<script src="js/query.js"></script>` (to use the `QueryDB()` function).
2. Make data object depending on required parameters. Example: `const data = JSON.stringify({name: "Bob", present: true})`.
3. Pass parameters to `QueryDB(action, data, callbackFunction(returnValue))` function. Callback function parameter is not required.
4. If you provided a callback function the `returnValue` will already be decoded. The format of the returned value is `{success: bool, data: json.object, errors: string}`. To access success `returnValue.success`, to access information in data `returnValue.data.theValue`

## Types of Users
* 'admin'
* 'teacher'
* 'floater'
* 'carer'
* 'child'

## Available Actions
List of actions and data parameters required
* 'new_user'
  * admin `{name: string, email: string, pin: int}`
  * teacher `{name: string, email: string, pin: int}`
  * floater `{name: string, email: string, pin: int}`
  * carer `{name: string, relation: string, email: string}`
  * child `{name: string, pin: int}`
* 'delete_user' `{id: int}`
* 'make_report' `{reports: object}`
* 'get_report' `{}`
* 'change_email' `{id: int, email: string}`
* 'change_pin' `{id: int, pin: int}`
* 'change_name' `{id: int, name: string}`
* 'check_pin' `{id: int, pin: int or equivalent string}`
* 'get_children' `{}`
* 'change_presence' `{id: int, presence: int (either 1 or 0)}`
