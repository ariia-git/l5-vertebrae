<?php namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;
use Illuminate\Support\MessageBag;

class AbstractController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @var mixed
     */
    protected $count;

    /**
     * @var mixed
     */
    protected $filter;

    /**
     * @var
     */
    protected $guard;

    /**
     * @var
     */
    protected $messages;

    /**
     * @var mixed
     */
    protected $page;

    /**
     * @var mixed
     */
    protected $perPage;

    /**
     * @var
     */
    protected $service;

    /**
     * @var mixed
     */
    protected $sort;

    public function __construct()
    {
        $this->filter = \Request::get('filter', []);
        $this->page = \Request::get('page', 1);
        $this->sort = \Request::get('sort', []);

        // todo: config for per page count
        $this->perPage = config('results-per-page', 15);
        $this->count = \Request::get('count', $this->perPage);
    }

    /**
     * Add message to MessageBag
     *
     * @return $this
     */
    protected function messages()
    {
        if (empty($this->messages)) {
            $this->messages = new MessageBag;
        }

        if (func_num_args() === 1) {
            $message = func_get_arg(0);

            if (is_array($message)) {
                $this->messages->merge($message);
            } else {
                $this->messages->add('info', $message);
            }
        } elseif (func_num_args() === 2) {
            $key = func_get_arg(0);
            $message = func_get_arg(1);

            $this->messages->add($key, $message);
        }

        return $this;
    }
}
