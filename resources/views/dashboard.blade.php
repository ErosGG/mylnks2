<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $paperera ? __("Paperera") : __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    @if (! $paperera)
                        <p>Hi, {{ $user->name }}</p>
                        <p>You're logged in!</p>
                        <div>
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
                        </div>

                        <br>

                        <div>
                            <form method="POST" action="{{ route("dashboard.create") }}">
                                @csrf
                                <div class="card mb-3 bg-dark border-sublime nl-add-new-link">
                                    <div class="card-body">
                                        <input class="form-control bg-dark pl-0 text-white fs-5 ts-bigger"
                                               type="text" name="title" value="{{ old("title") }}" placeholder="Títol d'exemple">
                                        <br>
                                        <input class="form-control bg-dark pl-0 text-white fs-5 ts-bigger"
                                               type="url" name="url" value="{{ old("url") }}" placeholder="https://www.exemple.cat/">
                                        <br>
                                        <select class="form-control bg-dark pl-0 text-white fs-5 ts-bigger"
                                                id="state">
                                            <option value=""> Trieu una opció </option>
                                            <option value="draft">Borrador</option>
                                            <option value="published">Publicat</option>
                                            <option value="restricted">Restringit</option>
                                        </select>
                                        <br>
                                        <input class="form-control bg-dark pl-0 text-white fs-5 ts-bigger"
                                               type="date" id="published_at" name="published_at"
                                               value="{{ date("Y-m-d") }}">
                                        <br>
                                        <input class="form-control bg-dark pl-0 text-white fs-5 ts-bigger"
                                               type="password" name="password" value="{{ old("password") }}" placeholder="Pa2sW0rd!">
                                        <br>
                                        <input class="btn btn-primary"
                                               type="submit" value="Afegeix">
                                    </div>
                                </div>
                            </form>
                        </div>

                        <br>
                    @endif

                    <div>
                        @forelse ($links as $link)
                            @if ($link->trashed())
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <h5 class="card-title fw-bold">{{ $link->title }}</h5>
                                        <p class="card-text text-black-50">{{ $link->url }}</p>
                                        <form action="{{ route("dashboard.restore", $link) }}" method="POST">
                                            @csrf
                                            @method("PATCH")
                                            <button type="submit" class="btn btn-link"><i class="bi bi-bootstrap-reboot"></i></button>
                                            Restore
                                        </form>
                                        <form action="{{ route("dashboard.destroy", $link) }}" method="POST">
                                            @csrf
                                            @method("DELETE")
                                            <!--
                                            <a href="{{ route("dashboard.details", $link) }}" class="btn btn-link"><i class="bi bi-zoom-in"></i></a>
                                            <a href="{{ route("dashboard.editor", $link) }}" class="btn btn-link"><i class="bi bi-pencil-square"></i></a>
                                            -->
                                            <button type="submit" class="btn btn-link"><i class="bi bi-x-circle"></i></button>
                                            Destroy
                                        </form>
                                    </div>
                                </div>
                            @else
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <h5 class="card-title fw-bold">{{ $link->title }}</h5>
                                        <p class="card-text text-black-50">{{ $link->url }}</p>
                                        <form action="{{ route("dashboard.delete", $link) }}" method="POST">
                                            @csrf
                                            @method("PATCH")
                                            <a href="{{ route("dashboard.details", $link) }}" class="btn btn-link"><i class="bi bi-zoom-in"></i></a>
                                            <a href="{{ route("dashboard.editor", $link) }}" class="btn btn-link"><i class="bi bi-pencil-square"></i></a>
                                            <button type="submit" class="btn btn-link"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </div>
                                </div>
                            @endif
                        @empty
                            <p>No hi ha links per a mostrar</p>
                        @endforelse
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
