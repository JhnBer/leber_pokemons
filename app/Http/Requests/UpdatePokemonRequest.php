<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePokemonRequest extends FormRequest
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
            'name' => ['string', 'min:3', 'max:32', 'unique:pokemons,name'],
            'shape' => ['string', Rule::in(PokemonShapes::array())],
            'location_id' => ['integer', Rule::exists('locations', 'id')],
            'abilities' => ['array'],
            'abilities.*' => ['integer', Rule::exists('abilities', 'id')],
            'image' => ['image', 'mimes:png,jpg,jpeg'],
        ];
    }
}
