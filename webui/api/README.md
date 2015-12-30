# rest - A RESTful web service based on PHP and Silex
`rest` is the web service used by oscar.

    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    
    $app->post('/feedback', function (Request $request) {
        $message = $request->get('message');
        mail('feedback@yoursite.com', '[YourSite] Feedback', $message);
        
        return new Response('Thank you for your feedback!', 201);
    });

## Using rest
The purpose of oscar rest is to allow the Android app **oscar** to interact with the system.
