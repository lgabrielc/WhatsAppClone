<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;
use App\Models\User;
use App\Rules\InvalidEmail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $contacts = Contact::paginate();
        $contacts = auth()->user()->contacts()->paginate();
        return view('contacts.index', compact('contacts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('contacts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => [
                'required',
                'email',
                'exists:users,email',
                //Validacion de no agregar el mismo usuario logeado como otro contacto
                Rule::notIn([auth()->user()->email]),
                //Validacion de no agregar contactos duplicados validado por email
                new InvalidEmail()
            ],
        ]);
        $user = User::where('email', $request->email)->first();
        $contact = Contact::create([
            'name' => $request->name,
            'user_id' => auth()->user()->id,
            'contact_id' => $user->id,
        ]);
        session()->flash('flash.banner', 'El contacto se ha creado satisfactoriamente');
        session()->flash('flash.bannerStyle', 'success');
        return redirect()->route('contacts.edit', $contact);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  Contact $contact
     * @return \Illuminate\Http\Response
     */
    public function show(Contact $contact)
    {
        return view('contacts.show', compact($contact));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  Contact $contact
     * @return \Illuminate\Http\Response
     */
    public function edit(Contact $contact)
    {
        // dd($contact);
        return view('contacts.edit', compact('contact'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  Contact $contact
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Contact $contact)
    {
        $request->validate([
            'name' => 'required',
            'email' => [
                'required',
                'email',
                'exists:users,email',
                //Validacion de no agregar el mismo usuario logeado como otro contacto
                Rule::notIn([auth()->user()->email]),
                //Validacion de no agregar contactos duplicados validado por email| excepto si edito el mio
                new InvalidEmail($contact->user->email),
            ],
        ]);
        // return $request->all();
        $user = User::where('email', $request->email)->first();
        $contact->update([
            'name' => $request->name,
            'contact_id' => $user->id,
        ]);
        session()->flash('flash.banner', 'El contacto se actualizo satisfactoriamente');
        session()->flash('flash.bannerStyle', 'success');
        return redirect()->route('contacts.edit', $contact);
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  Contact $contact
     * @return \Illuminate\Http\Response
     */
    public function destroy(Contact $contact)
    {
        $contact->delete();
        session()->flash('flash.banner', 'El contacto se eliminó con exito');
        session()->flash('flash.bannerStyle', 'success');
        return redirect()->route('contacts.index');
    }
}
