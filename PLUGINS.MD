## Plugin Documentation

Basic stuff works, not worried about plugins right now. This will get better over time.

## Plugin formatting

In order to call an event used in a plugin you must prepend `on` to it: `onEventName`.
As an example, for the `PageLoad` event it would be `onPageLoad`:

```
class ExamplePlugin extends Plugin{
	public function __construct(){
		//this is required by the plugin system to get working properly. This adds all the below events to global like {Class}::{onEventName};
		parent::__construct(); //Required
		return true;
	}
	
	public function initialize(){
		return true;
	}
	public function onPageLoad(&$sess, &$post){
		
		echo '<p class="lead">This came from the <i>Example</i> plugin!</p>';
		
		return true;
	}
}
```

## Plugin Calls

- ApiLoad(&$_POST)

Called every time the API is called (Post, like, comment, change user setting, login, register) Can be used to restrict registrations as an example, or to bypass login to a different system (Like LDAP!)

- ApiCustom(&$_SESSION, &$_POST)

Called when an API type is either not specified or the called type does not exist in the standard API type list.

- IndexCustom(&$_SESSION, &$args)

Called when the index page cannot find a URI callback function (E.G. /user/username is called but /example/what does not exist so it is then called to this plugin)
	
	$args['type'] is first part of the URI /type
	$args['page'] is the second part of the URI /type/page
	$args['params'][] every other part of the URI /type/page/param[0]/param[1]/etc/etc

- IndexApiLoad(&$args)

Called every time the Index API is called (Load single post, load user profile, etc)

- LoggedOutNavEnd(&$_SESSION)

Logged out navbar end (Good for adding more links to navbar)

- LoggedInNavEnd(&$_SESSION)

Logged in navbar end (Good for adding more links to navbar for logged in users like custom pages, etc.)

- HeaderEnd(&$_SESSION)

End of header, good for adding custom CSS or styling to EACH page.

- BodyEnd(&$_SESSION)

End of body, good for adding Javascript, or other data to pages.

- PageLoad(&$_SESSION, &$_POST)

start of page load