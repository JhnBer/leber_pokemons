<?php

namespace App\Http\Requests;

use App\Enums\PokemonShapes;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePokemonRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3', 'max:32', 'unique:pokemons,name'],
            'shape' => ['required', 'string', Rule::in(PokemonShapes::array())],
            'location_id' => ['required', 'integer', Rule::exists('locations', 'id')],
            'abilities' => ['required', 'array'],
            'abilities.*' => ['required', 'integer', Rule::exists('abilities', 'id')],
            'image' => ['required', 'image', 'mimes:png,jpg,jpeg'],
        ];
    }
}
