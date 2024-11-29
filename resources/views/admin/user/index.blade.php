@extends('layouts.admin.app')

@section('content')
    <div class="container mx-auto p-4">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="container mx-auto px-4">
                <p class="text-2xl font-bold mb-6">User Management</p>
                <button id="openModal"
                    class="bg-white border border-blue-500 text-blue-500 px-4 py-1 rounded-md shadow-md hover:bg-blue-600 hover:text-white">
                    Tambah User
                </button>

                <div id="modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
                    <div
                        class="relative bg-white w-full max-w-4xl mx-auto p-6 rounded-lg shadow-lg overflow-auto max-h-screen mt-10">
                        <button id="closeModal"
                            class="absolute top-4 right-4 text-gray-500 hover:text-gray-700 focus:outline-none">
                            &times;
                        </button>

                        <p class="text-xl font-bold text-gray-800 mb-4">Tambah User</p>
                        <form method="POST" action="{{ route('user.store') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-4">
                                <label for="name" class="block text-sm font-medium text-gray-700">Nama</label>
                                <input id="name" name="name" type="text" required
                                    class="mt-1 block w-full px-4 py-2 border rounded-md shadow-sm">
                                @error('name')
                                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                <input id="email" name="email" type="email" required
                                    class="mt-1 block w-full px-4 py-2 border rounded-md shadow-sm">
                                @error('email')
                                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                                <input id="password" name="password" type="password" required
                                    class="mt-1 block w-full px-4 py-2 border rounded-md shadow-sm">
                                @error('password')
                                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- <div class="mb-4">
                        <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                        <select id="role" name="role" required
                            class="mt-1 block w-full px-4 py-2 border rounded-md shadow-sm">
                            <option value="admin">Admin</option>
                            <option value="user" selected>User</option>
                        </select>
                    </div> --}}

                            <hr class="my-4">
                            <p class="text-lg font-semibold text-gray-800 mb-4">Biodata Karyawan</p>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="nip" class="block text-sm font-medium text-gray-700">NIP</label>
                                    <input id="nip" name="nip" type="text" required
                                        class="mt-1 block w-full px-4 py-2 border rounded-md shadow-sm">
                                    @error('nip')
                                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="telpon" class="block text-sm font-medium text-gray-700">Telepon</label>
                                    <input id="telpon" name="telpon" type="text" required
                                        class="mt-1 block w-full px-4 py-2 border rounded-md shadow-sm">
                                    @error('telpon')
                                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700">Jenis
                                        Kelamin</label>
                                    <select id="jenis_kelamin" name="jenis_kelamin" required
                                        class="mt-1 block w-full px-4 py-2 border rounded-md shadow-sm">
                                        <option value="Laki-laki">Laki-laki</option>
                                        <option value="Perempuan">Perempuan</option>
                                    </select>
                                    @error('jenis_kelamin')
                                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="tempat_lahir" class="block text-sm font-medium text-gray-700">Tempat
                                        Lahir</label>
                                    <input id="tempat_lahir" name="tempat_lahir" type="text" required
                                        class="mt-1 block w-full px-4 py-2 border rounded-md shadow-sm">
                                    @error('tempat_lahir')
                                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="tgl_lahir" class="block text-sm font-medium text-gray-700">Tanggal
                                        Lahir</label>
                                    <input id="tgl_lahir" name="tgl_lahir" type="date" required
                                        class="mt-1 block w-full px-4 py-2 border rounded-md shadow-sm">
                                    @error('tgl_lahir')
                                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="agama" class="block text-sm font-medium text-gray-700">Agama</label>
                                    <select id="agama" name="agama" required
                                        class="mt-1 block w-full px-4 py-2 border rounded-md shadow-sm">
                                        <option value="Islam">Islam</option>
                                        <option value="Kristen">Kristen</option>
                                        <option value="Katolik">Katolik</option>
                                        <option value="Budha">Budha</option>
                                        <option value="Hindu">Hindu</option>
                                        <option value="Konghuchu">Konghuchu</option>
                                    </select>
                                    @error('agama')
                                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="alamat" class="block text-sm font-medium text-gray-700">Alamat</label>
                                <textarea id="alamat" name="alamat" required class="mt-1 block w-full px-4 py-2 border rounded-md shadow-sm"></textarea>
                                @error('alamat')
                                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="cover" class="block text-sm font-medium text-gray-700">Foto</label>
                                <input id="cover" name="cover" type="file" accept=".jpg,.jpeg,.png"
                                    class="mt-1 block w-full px-4 py-2 border rounded-md shadow-sm">
                                @error('cover')
                                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex justify-end space-x-2">
                                <button type="button" id="cancelModal"
                                    class="bg-white border border-gray-500 text-gray-500 px-4 py-1 rounded-md shadow-md hover:bg-gray-600 hover:text-white">Batal</button>
                                <button type="submit"
                                    class="bg-white border border-blue-500 text-blue-500 px-4 py-1 rounded-md shadow-md hover:bg-blue-600 hover:text-white">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <table class="min-w-full bg-white mt-6 border rounded-lg shadow-lg">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">#</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Nama</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Email</th>
                        {{-- <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Role</th> --}}
                        <th class="px-4 py-2 text-center text-sm font-medium text-gray-700">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-2 text-sm text-gray-600">{{ $loop->iteration }}</td>
                            <td class="px-4 py-2 text-sm text-gray-600">{{ $user->name }}</td>
                            <td class="px-4 py-2 text-sm text-gray-600">{{ $user->email }}</td>
                            {{-- <td class="px-4 py-2 text-sm text-gray-600">{{ ucfirst($user->role) }}</td> --}}

                            <td class="px-4 py-2 text-center">
                                <div class="flex items-center justify-center space-x-2">
                                    <a href="{{ route('user.show', $user->id) }}"
                                        class="bg-white border border-blue-500 text-blue-500 px-2 py-1 rounded-md hover:bg-blue-600 hover:text-white">
                                        Show
                                    </a>
                                    <form action="{{ route('user.destroy', $user->id) }}" method="POST"
                                        id="deleteForm-{{ $user->id }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                            class="bg-white border border-red-500 text-red-500 px-4 py-1 rounded-md shadow-md hover:bg-red-600 hover:text-white"
                                            onclick="confirmDelete({{ $user->id }})">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Script untuk Modal -->
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

            <script>
                function confirmDelete(userId) {
                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: "Data ini akan dihapus secara permanen!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById(`deleteForm-${userId}`).submit();
                        }
                    });
                }
            </script>

        </div>
    </div>
@endsection
