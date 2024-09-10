<x-midas-layout>
    <x-slot name="header">
        <x-bread :area="$area" :page="$page" :rota="$rota"></x-bread>
    </x-slot>

    <div class="w-full">
        <div class="mx-auto w-full sm:px-4 lg:px-6">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-8 text-gray-900">
                    <form action="{{ route('admin.situacoes.update', $situacao) }}" class="mt-3" method="post">
                        @csrf @method('PATCH')
                        <div class="flex flex-col">
                            <label for="descricao_operacao" class="text-black">Descrição da Operação</label>
                            <input type="text" name="descricao_situacao" id="descricao_situacao" value="{{ old('descricao_situacao') ?? $situacao->descricao_situacao }}"
                                class="mt-2 rounded-lg border-gray-300 py-1 focus:border-gray-300 focus:outline-none focus:ring-0">
                        </div>
                        <div class="flex flex-col">
                            <label for="motivo_situacao" class="text-black">Motivo Situação</label>
                            <input type="text" name="motivo_situacao" id="motivo_situacao" value="{{ old('motivo_situacao') }}"
                                class="mt-2 rounded-lg border-gray-300 py-1 focus:border-gray-300 focus:outline-none focus:ring-0">
                        </div>
                        <button class="mt-3 rounded-md bg-gray-300 px-6 py-1.5 text-gray-500 transition duration-150 hover:bg-green-700 hover:text-slate-50 hover:shadow-xl">
                            <i class="bi bi-floppy mr-1"></i>
                            Atualizar
                        </button>
                        <a href="{{ route('admin.situacoes.index') }}"
                            class="mt-3 rounded-md bg-orange-500 px-6 py-2 font-semibold text-gray-800 transition duration-150 hover:bg-orange-400 hover:text-slate-50 hover:shadow-xl">Voltar</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-midas-layout>