<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome - Set Up Profile</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>


    @vite('resources/css/app.css')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/framer-motion/10.12.0/framer-motion.min.js" defer></script>
    <style>
        .background-animation {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            overflow: hidden;
            z-index: -1;
        }

        .icon {
            position: absolute;
            font-size: 2rem;
            color: rgba(255, 255, 255, 0.2);
            transition: transform 0.1s ease-out;
        }
    </style>
</head>

<body
    class="flex items-center justify-center min-h-screen bg-gradient-to-r from-black via-gray-900 to-black text-white relative overflow-hidden">
    <div class="background-animation" id="background-animation"></div>
    <!-- Lingkaran Blur dengan Glow -->
    <div
        class="fixed top-40 -left-52 md:top-52 lg:top-80 lg:-left-40 w-[400px] h-[400px] bg-green-400 rounded-full blur-3xl opacity-50 shadow-lg shadow-green-500/50 -z-10 animate-moveCircle1">
    </div>
    <div
        class="fixed -top-44 -right-56 lg:-top-64 lg:-right-52 w-[420px] h-[420px] bg-pink-400 rounded-full blur-3xl opacity-50 shadow-lg shadow-pink-500/50 -z-10 animate-moveCircle2">
    </div>

    <!-- Modal untuk Crop Gambar -->
    <div id="cropperModal" class="fixed inset-0 flex z-10 items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-gray-900 p-6 rounded-lg w-96">
            <h2 class="text-white text-lg mb-4">Crop Profile Photo</h2>
            <div class="w-full h-64">
                <img id="imagePreview" class="w-full h-full object-cover">
            </div>
            <div class="flex justify-end mt-4">
                <button id="cancelCrop" class="px-4 py-2 bg-gray-600 text-white rounded mr-2">Cancel</button>
                <button id="saveCrop" class="px-4 py-2 bg-blue-600 text-white rounded">Crop & Save</button>
            </div>
        </div>
    </div>


    <div id="setup-container"
        class="w-3/4 max-w-4xl bg-gray-800 rounded-2xl shadow-lg overflow-hidden flex relative ">
        <div class="hidden w-1/2 p-10 justify-center items-center md:block md:items-start">
            <img src="{{ asset('images/logomwl.png') }}" alt="MyWatchLog Logo" class="w-auto h-20 md:h-24">
        </div>

        <div class="w-full md:w-1/2 p-10 bg-gray-900 rounded-2xl">
            <h2 class="text-2xl font-bold text-center text-blue-400">Welcome, {{ auth()->user()->name }}!</h2>
            <p class="text-gray-300 text-center">Set up your profile before continuing</p>

            <form action="{{ route('welcome.save') }}" method="POST" enctype="multipart/form-data" class="mt-6">
                @csrf

                <div class="mb-4">
                    <label class="block text-gray-300">Username</label>
                    <input type="text" name="username" required
                        class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-md focus:ring-2 focus:ring-blue-400 text-white"
                        placeholder="Choose a username">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-300">Profile Photo</label>
                    <input type="file" id="profileInput" name="profile_photo" accept="image/*"
                        class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-md text-white">
                    <input type="hidden" name="cropped_image" id="croppedImage">

                </div>

                <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-md hover:bg-blue-700">
                    Save & Continue
                </button>
            </form>
        </div>
    </div>

    <script>
        const icons = ["🎬", "🎥", "📽️", "🍿", "🎞️"];
        const numIcons = 20;
        const backgroundAnimation = document.getElementById("background-animation");

        for (let i = 0; i < numIcons; i++) {
            let span = document.createElement("span");
            span.innerHTML = icons[Math.floor(Math.random() * icons.length)];
            span.classList.add("icon");
            span.style.top = Math.random() * 100 + "vh";
            span.style.left = Math.random() * 100 + "vw";
            span.style.transform = `scale(${Math.random() * 1.5 + 0.5})`;
            backgroundAnimation.appendChild(span);
        }

        document.addEventListener("mousemove", (event) => {
            document.querySelectorAll(".icon").forEach((icon) => {
                let moveX = (event.clientX / window.innerWidth - 0.5) * 30;
                let moveY = (event.clientY / window.innerHeight - 0.5) * 30;
                icon.style.transform =
                    `translate(${moveX}px, ${moveY}px) scale(${Math.random() * 1.5 + 0.5})`;
            });
        });

        document.addEventListener("DOMContentLoaded", function() {
            const setupContainer = document.getElementById("setup-container");
            setupContainer.style.opacity = 0;
            setupContainer.style.transform = "translateY(50px)";
            setTimeout(() => {
                setupContainer.style.transition = "all 0.6s ease-out";
                setupContainer.style.opacity = 1;
                setupContainer.style.transform = "translateY(0)";
            }, 100);
        });

        document.addEventListener("DOMContentLoaded", function() {
            const usernameInput = document.querySelector('input[name="username"]');
            const feedback = document.createElement("p");
            feedback.classList.add("text-sm", "mt-1");
            usernameInput.parentNode.appendChild(feedback);

            usernameInput.addEventListener("input", function() {
                const username = usernameInput.value.trim();

                if (username.length < 3) {
                    feedback.textContent = "";
                    return;
                }

                fetch(`/check-username?username=${username}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.exists) {
                            feedback.textContent = "Username is already taken!";
                            feedback.classList.remove("text-green-400");
                            feedback.classList.add("text-red-400");
                        } else {
                            feedback.textContent = "Username is available!";
                            feedback.classList.remove("text-red-400");
                            feedback.classList.add("text-green-400");
                        }
                    })
                    .catch(error => console.error("Error checking username:", error));
            });
        });

        document.addEventListener("DOMContentLoaded", function() {
            let cropper;
            const profileInput = document.getElementById("profileInput");
            const cropperModal = document.getElementById("cropperModal");
            const imagePreview = document.getElementById("imagePreview");
            const saveCrop = document.getElementById("saveCrop");
            const cancelCrop = document.getElementById("cancelCrop");
            const croppedImageInput = document.getElementById("croppedImage");

            profileInput.addEventListener("change", function(event) {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        imagePreview.src = e.target.result;
                        cropperModal.classList.remove("hidden");

                        // Hapus instance cropper sebelumnya jika ada
                        if (cropper) {
                            cropper.destroy();
                        }

                        // Inisialisasi Cropper.js
                        cropper = new Cropper(imagePreview, {
                            aspectRatio: 1,
                            viewMode: 2,
                            autoCropArea: 1,
                        });
                    };
                    reader.readAsDataURL(file);
                }
            });

            // Simpan hasil crop
            saveCrop.addEventListener("click", function() {
                const croppedCanvas = cropper.getCroppedCanvas();
                croppedImageInput.value = croppedCanvas.toDataURL("image/jpeg");

                cropperModal.classList.add("hidden");
            });

            // Batalkan crop
            cancelCrop.addEventListener("click", function() {
                cropperModal.classList.add("hidden");
                profileInput.value = "";
            });
        });
    </script>
</body>

</html>
