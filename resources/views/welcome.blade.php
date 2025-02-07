<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome - Set Up Profile</title>
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

    <div id="setup-container"
        class="w-3/4 max-w-4xl bg-gray-800 rounded-2xl shadow-lg overflow-hidden flex relative z-10">
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
                    <input type="file" name="profile_photo" accept="image/*"
                        class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-md text-white">
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
    </script>
</body>

</html>
