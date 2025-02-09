@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto py-12 px-6">
        <h2 class="text-3xl font-bold text-white mb-6">Edit Profile</h2>

        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data"
            class="bg-gray-800 p-6 rounded-lg shadow-lg">
            @csrf

            <div class="mb-4">
                <label class="block text-gray-400">Profile Photo</label>
                <div class="flex items-center mt-2">
                    <img id="profile-preview" src="{{ asset('storage/' . $user->profile_photo) }}"
                        class="w-20 h-20 rounded-full border-4 border-white object-cover">
                    <input type="file" name="profile_photo" id="profile_photo"
                        class="ml-4 p-2 bg-gray-700 text-white rounded-lg">
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-gray-400">Name</label>
                <input type="text" name="name" value="{{ $user->name }}"
                    class="w-full p-2 bg-gray-700 text-white rounded-lg">
            </div>

            <div class="mb-4">
                <label class="block text-gray-400">Username</label>
                <input type="text" name="username" id="username" value="{{ $user->username }}"
                    class="w-full p-2 bg-gray-700 text-white rounded-lg">
                <p id="username-error" class="text-red-500 text-sm mt-1 hidden">Username already taken</p>
            </div>

            <button type="submit" id="submit-btn"
                class="px-6 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg">Save Changes</button>
            <a href="{{ route('profile') }}"
                class="px-6 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg ml-2">Cancel</a>
        </form>
    </div>

    <!-- Modal untuk cropping -->
    <div id="crop-modal" class="fixed inset-0 bg-black bg-opacity-75 flex justify-center items-center hidden">
        <div class="bg-gray-800 p-6 rounded-lg shadow-lg w-96">
            <h3 class="text-white text-lg font-bold mb-4">Crop Profile Photo</h3>
            <div class="w-full h-60 overflow-hidden">
                <img id="crop-image" class="w-full">
            </div>
            <div class="flex justify-end mt-4">
                <button id="cancel-crop" class="px-4 py-2 bg-gray-500 text-white rounded-lg mr-2">Cancel</button>
                <button id="save-crop" class="px-4 py-2 bg-blue-500 text-white rounded-lg">Save</button>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css">

    <script>
        document.getElementById('username').addEventListener('input', function() {
            let username = this.value;
            let errorText = document.getElementById('username-error');
            let submitBtn = document.getElementById('submit-btn');

            if (username.length > 0) {
                fetch(`/check-username?username=${username}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.exists) {
                            errorText.classList.remove('hidden');
                            submitBtn.disabled = true;
                            submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                        } else {
                            errorText.classList.add('hidden');
                            submitBtn.disabled = false;
                            submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                        }
                    });
            } else {
                errorText.classList.add('hidden');
                submitBtn.disabled = false;
                submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            }
        });

        let profileInput = document.getElementById('profile_photo');
        let profilePreview = document.getElementById('profile-preview');
        let cropModal = document.getElementById('crop-modal');
        let cropImage = document.getElementById('crop-image');
        let cropper;

        profileInput.addEventListener('change', function(event) {
            let file = event.target.files[0];
            if (file) {
                let reader = new FileReader();
                reader.onload = function(e) {
                    cropImage.src = e.target.result;
                    cropModal.classList.remove('hidden');
                    if (cropper) cropper.destroy();
                    cropper = new Cropper(cropImage, {
                        aspectRatio: 1,
                        viewMode: 2,
                    });
                };
                reader.readAsDataURL(file);
            }
        });

        document.getElementById('cancel-crop').addEventListener('click', function() {
            cropModal.classList.add('hidden');
        });

        document.getElementById('save-crop').addEventListener('click', function() {
            let canvas = cropper.getCroppedCanvas({
                width: 200,
                height: 200
            });
            canvas.toBlob(function(blob) {
                let file = new File([blob], 'cropped.jpg', {
                    type: 'image/jpeg'
                });
                let dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                profileInput.files = dataTransfer.files;
                profilePreview.src = URL.createObjectURL(blob);
                cropModal.classList.add('hidden');
            }, 'image/jpeg');
        });
    </script>
@endsection
