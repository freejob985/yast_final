<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

use Mail;
use Carbon\Carbon;
use DB;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Exception $e)
    {
          dd($e);
        $mutable = Carbon::now()  ;
        if ($e instanceof \Exception) {
            $data['file'] = $e->getFile();
            $data['code'] = $e->getCode();
            $data['line'] = $e->getLine();
            $data['message'] = $e->getMessage();
            $data['function'] = $e->getTrace()[0]['function'];
            $data['class'] = $e->getTrace()[0]['class'];
            $path = (array) \Route::getCurrentRoute();
            $data['middleware'] = $path['action']['middleware'][0];
            $data['uses'] = $path['action']['uses'];
            $data['controller'] = $path['action']['controller'];
            $data['namespace'] = $path['action']['namespace'];
            $data['prefix'] = $path['action']['prefix'];
            $data['name'] = "yasta";
            Mail::send('/mail', ['data' => $data], function ($m) use ($data) {
                $m->to('mr.bean.mg22@gmail.com')->subject( $data['message'])->getSwiftMessage()
                    ->getHeaders()
                    ->addTextHeader('x-mailgun-native-send', 'true');
                $m->from('yasta@yasta.net', 'yasta');
            });
            
            DB::table('report')->insert($data);
           
        }

        // Pass the error on to continue processing
        return parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Exception
     */
    public function render($request, Exception $exception)
    {  
        
      //dd($exception);
        return parent::render($request, $exception);
    }
}
