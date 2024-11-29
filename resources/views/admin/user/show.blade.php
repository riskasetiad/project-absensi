        @extends('layouts.admin.app')

        @section('content')
            <div class="container mx-auto p-4">
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <div class="flex items-center justify-between">
                        <p class="text-2xl font-bold text-gray-800">Detail User</p>
                        <button id="openModal"
                            class="bg-white border border-blue-500 text-blue-500 px-4 py-1 rounded-md shadow-md hover:bg-blue-600 hover:text-white">
                            Edit
                        </button>
                    </div>
                    <hr class="my-4">

                    <div class="mt-4">
                        @if ($user->karyawan->cover)
                            <img src="{{ asset('images/cover/' . $user->karyawan->cover) }}" alt="Cover"
                                class="w-32 h-32 object-cover rounded-md">
                        @else
                            <p>No photo available</p>
                        @endif
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                        <div>
                            <div class="mt-4">
                                <p class="text-lg font-semibold text-gray-700">Nama</p>
                                <p>{{ $user->name }}</p>
                            </div>

                            <div class="mt-4">
                                <p class="text-lg font-semibold text-gray-700">Email</p>
                                <p>{{ $user->email }}</p>
                            </div>

                            <div class="mt-4">
                                <p class="text-lg font-semibold text-gray-700">NIP</p>
                                <p>{{ $user->karyawan->nip }}</p>
                            </div>

                            <div class="mt-4">
                                <p class="text-lg font-semibold text-gray-700">Telepon</p>
                                <p>{{ $user->karyawan->telpon }}</p>
                            </div>
                        </div>

                        <div>
                            <div class="mt-4">
                                <p class="text-lg font-semibold text-gray-700">Jenis Kelamin</p>
                                <p>{{ $user->karyawan->jenis_kelamin }}</p>
                            </div>

                            <div class="mt-4">
                                <p class="text-lg font-semibold text-gray-700">Tempat Tanggal Lahir</p>
                                <p>{{ $user->karyawan->tempat_lahir }},
                                    {{ \Carbon\Carbon::parse($user->karyawan->tgl_lahir)->translatedFormat('d F Y') }}</p>
                            </div>

                            <div class="mt-4">
                                <p class="text-lg font-semibold text-gray-700">Agama</p>
                                <p>{{ $user->karyawan->agama }}</p>
                            </div>

                            <div class="mt-4">
                                <p class="text-lg font-semibold text-gray-700">Alamat</p>
                                <p>{{ $user->karyawan->alamat }}</p>
                            </div>
                        </div>

                        <div class="mt-6">
                            <a href="{{ route('user.index') }}" class="text-blue-500 hover:text-blue-700">Kembali</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Edit -->
            <div id="modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
                <div
                    class="relative bg-white w-full max-w-4xl mx-auto p-6 rounded-lg shadow-lg overflow-auto max-h-screen mt-10">
                    <!-- Close Button -->
                    <button id="closeModal"
                        class="absolute top-4 right-4 text-gray-500 hover:text-gray-700 focus:outline-none">
                        &times;
                    </button>

                    <h2 class="text-xl font-bold text-gray-800 mb-4">Edit Detail User</h2>
                    <form action="{{ route('user.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">Nama</label>
                            <input id="name" type="text" name="name" value="{{ $user->name }}" required
                                class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md">
                                 @error('name')
                                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input id="email" type="email" name="email" value="{{ $user->email }}" required
                                class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md">
                                 @error('email')
                                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="nip" class="block text-sm font-medium text-gray-700">NIP</label>
                            <input id="nip" type="text" name="nip" value="{{ $user->karyawan->nip }}" required
                                class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md">
                                 @error('nip')
                                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="telpon" class="block text-sm font-medium text-gray-700">Telepon</label>
                            <input id="telpon" type="text" name="telpon" value="{{ $user->karyawan->telpon }}"
                                required class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md">
                                 @error('telpon')
                                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700">Jenis Kelamin</label>
                            <select id="jenis_kelamin" name="jenis_kelamin" required
                                class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md">
                                <option value="Laki-laki"
                                    {{ old('jenis_kelamin', $user->karyawan->jenis_kelamin) == 'Laki-laki' ? 'selected' : '' }}>
                                    Laki-laki</option>
                                <option value="Perempuan"
                                    {{ old('jenis_kelamin', $user->karyawan->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>
                                    Perempuan</option>
                            </select>
                            @error('jenis_kelamin')
                                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="tempat_lahir" class="block text-sm font-medium text-gray-700">Tempat Lahir</label>
                            <input id="tempat_lahir" type="text" name="tempat_lahir"
                                value="{{ old('tempat_lahir', $user->karyawan->tempat_lahir) }}" required
                                class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md">
                            @error('tempat_lahir')
                                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="tgl_lahir" class="block text-sm font-medium text-gray-700">Tanggal Lahir</label>
                            <input id="tgl_lahir" type="date" name="tgl_lahir"
                                value="{{ old('tgl_lahir', \Carbon\Carbon::parse($user->karyawan->tgl_lahir)->format('Y-m-d')) }}"
                                required class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md">
                            @error('tgl_lahir')
                                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="agama" class="block text-sm font-medium text-gray-700">Agama</label>
                            <select id="agama" name="agama" required
                                class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md">
                                <option value="Islam"
                                    {{ old('agama', $user->karyawan->agama) == 'Islam' ? 'selected' : '' }}>Islam</option>
                                <option value="Kristen"
                                    {{ old('agama', $user->karyawan->agama) == 'Kristen' ? 'selected' : '' }}>Kristen
                                </option>
                                <option value="Katolik"
                                    {{ old('agama', $user->karyawan->agama) == 'Katolik' ? 'selected' : '' }}>Katolik
                                </option>
                                <option value="Budha"
                                    {{ old('agama', $user->karyawan->agama) == 'Budha' ? 'selected' : '' }}>Budha</option>
                                <option value="Hindu"
                                    {{ old('agama', $user->karyawan->agama) == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                                <option value="Konghuchu"
                                    {{ old('agama', $user->karyawan->agama) == 'Konghuchu' ? 'selected' : '' }}>Konghuchu
                                </option>
                            </select>
                            @error('agama')
                                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="alamat" class="block text-sm font-medium text-gray-700">Alamat</label>
                            <textarea id="alamat" name="alamat" required
                                class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md">{{ old('alamat', $user->karyawan->alamat) }}</textarea>
                            @error('alamat')
                                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="cover" class="block text-sm font-medium text-gray-700">Foto</label>
                            <img src="{{ asset('images/cover/' . $user->karyawan->cover) }}" width="100"
                                class="mb-2">
                            <input type="file" accept=".jpg,.jpeg,.png"
                                class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md" name="cover">
                                 @error('cover')
                                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mt-4 flex gap-2 justify-end">
                            <button type="submit"
                                class="bg-white border border-blue-500 text-blue-500 px-4 py-1 rounded-md shadow-md hover:bg-blue-600 hover:text-white">Update</button>
                        </div>
                    </form>
                </div>
            </div>
            </div>
        @endsection

        @section('scripts')
            <script>
                const modal = document.getElementById('modal');
                const openModalButton = document.getElementById('openModal');
                const closeModalButton = document.getElementById('closeModal');
                const cancelModalButton = document.getElementById('cancelModal');

                openModalButton.addEventListener('click', () => {
                    modal.classList.remove('hidden');
                });

                closeModalButton.addEventListener('click', () => {
                    modal.classList.add('hidden');
                });

                cancelModalButton.addEventListener('click', () => {
                    modal.classList.add('hidden');
                });
            </script>
        @endsection
