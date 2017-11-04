<?php namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

abstract class AbstractFormRequest extends FormRequest
{
    /**
     * @var array
     */
    protected $defaultInputs = [];

    /**
     * @param null $key
     * @param null $default
     * @return array|mixed|string
     */
    public function input($key = null, $default = null)
    {
        $input = parent::input($key, $default);

        if (is_null($input)) {
            foreach ($this->defaultInputs as $inputKey => $defaultValue) {
                if ($key == $inputKey) {
                    $input = $defaultValue;
                    break;
                }
            }
        }

        return $input;
    }

    /**
     * @param array $keys
     * @return array
     */
    public function inputs($keys = [])
    {
        $result = [];
        foreach ($keys as $key) {
            $result[$key] = $this->input($key);
        }

        return $result;
    }

    /**
     * @param array $keys
     * @return array
     */
    public function inputsWithDefaults($keys = [])
    {
        $result = [];
        foreach ($keys as $key => $default) {
            $result[$key] = $this->input($key, $default);
        }

        return $result;
    }
}
