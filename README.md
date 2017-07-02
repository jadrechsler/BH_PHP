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
  * admin `{name, email, pin}`
  * teacher `{name, email, pin}`
  * floater `{name, email, pin}`
  * carer `{name, relation, email}`
  * child `{name, pin}`
* 'delete_user' `{id}`
* 'make_report' `{reports: type json string}`
* 'get_report' `{}`
* 'change_email' `{id, email}`
* 'change_pin' `{id, pin}`
* 'change_name' `{id, name}`
* 'check_pin' `{id, pin}`
* 'get_children' `{}`
* 'change_presence' `{id, presence}`
