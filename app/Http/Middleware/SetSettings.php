<?php

namespace App\Http\Middleware;

use Closure;
class SetSettings
{

    /**
     * @param $request
     * @param Closure $next
     * @return mixed
     * @throws \Bosnadev\Repositories\Exceptions\RepositoryException
     */
    public function handle($request, Closure $next)
    {
        //check for lang
        if($request->header('lang')){
            $lang=$request->header('lang');
        }else{
            $lang=app()->getLocale();
        }
        app()->setLocale($lang);

        $data = ['general' => [], 'mail' => [], 'social' => []];
        $repo = new \App\Repositories\SettingRepository(app(), \Illuminate\Support\Collection::make());
        $setting = $repo->findWhereGroup('set_group', array_keys($data), ['key_id', 'value', 'is_object', 'set_group']);
        foreach ($data as $key => $value) {
            foreach ($setting as $v) {
                if ($v->set_group == $key) {
                    $data[$key][$v->key_id] = ($v->is_object == 1) ? json_decode($v->value, true) : $v->value;
                    if (is_array($data[$key][$v->key_id]) && array_key_exists(\App::getLocale(), $data[$key][$v->key_id])) {
                        $data[$key][$v->key_id] = $data[$key][$v->key_id][\App::getLocale()];
                    }
                }
            }
        }
        foreach ($data as $key => $value) {
            $data[$key] = json_encode($value);
        }
        $settings = $data;
        foreach ($settings as $key => $value) {
            $settings[$key] = json_decode($value, true);
        }
//        // set config data in to run time
        $mail = [
            'driver' => $settings['mail']['mail_driver'],
            'host' => $settings['mail']['mail_smtp_host'],
            'port' => $settings['mail']['mail_smtp_port'],
            'from' => ['address' => 'marwan_zk@yahoo.com', 'name' => 'Marwan'],
            'encryption' => $settings['mail']['mail_smtp_encryption'],
            'username' => $settings['mail']['mail_smtp_username'],
            'password' => $settings['mail']['mail_smtp_password'],
            'sendmail' => $settings['mail']['mail_sendmail_path']];
        $services = [
            'mailgun.domain' => $settings['mail']['mail_mailgun_domain'],
            'mailgun.secret' => $settings['mail']['mail_mailgun_secret'],
            'ses.key' => $settings['mail']['mail_ses_key'],
            'ses.secret' => $settings['mail']['mail_ses_secret'],
            'mandrill.secret' => $settings['mail']['mail_mandrill_secret'],
            'sparkpost.secret' => $settings['mail']['mail_sparkpost_secret']
        ];
        $settings['mail'] = $mail;
        $settings['services'] = $services;
        foreach ($settings as $set => $row) {
            foreach ($row as $key => $value) {
                \Config::set($set . '.' . $key, $value);
            }
        }
 /*       $app = \App::getInstance();
        $app['swift.transport'] = $app->singleton(function ($app)
        {
            return new \Illuminate\Mail\TransportManager($app);
        });
        $mailer = new \Swift_Mailer($app['swift.transport']->driver());
        \Mail::setSwiftMailer($mailer);*/

        return $next($request);
    }

}
