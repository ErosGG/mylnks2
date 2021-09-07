<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Link Editor') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <h5>Corregiu els errors següents:</h5>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route("dashboard.update", $link) }}">
                        @csrf
                        @method("PUT")
                        <div class="card mt-5 bg-sublime border-sublime nl-add-new-link">
                            <div class="card-body">
                                <input class="form-control bg-sublime pl-0 text-black ts-bigger"
                                       type="text" name="title" value="{{ old("title", $link->title) }}" placeholder="Títol d'exemple">
                                <br>
                                <input class="form-control bg-sublime pl-0 text-black ts-bigger"
                                       type="url" name="url" value="{{ old("url", $link->url) }}" placeholder="https://www.exemple.cat/">
                                <br>
                                <select class="form-control bg-sublime pl-0 text-black ts-bigger"
                                        id="state">
                                    <option value=""> Trieu una opció </option>
                                    <option value="draft">Borrador</option>
                                    <option value="published">Publicat</option>
                                    <option value="restricted">Restringit</option>
                                </select>
                                <br>
                                <input class="form-control bg-sublime pl-0 text-black ts-bigger"
                                       type="date" id="published_at" name="published_at"
                                       value="{{ date("Y-m-d") }}">
                                <br>
                                <input class="form-control bg-sublime pl-0 text-black ts-bigger"
                                       type="password" name="password" value="{{ old("password") }}" placeholder="Pa2sW0rd!">
                                <br>
                                <input class="btn btn-primary"
                                       type="submit" value="Actualitza">
                            </div>
                        </div>
                    </form>

                    <br>
                    <a href="{{ route("dashboard.index") }}">Tornar al dashboard</a>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
