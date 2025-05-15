@php
    $title = 'Minecraft Mods Translate to Japanese';
    $pageTitle = $pageName ? "$pageName | " : '';
@endphp
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>{{ $pageTitle }}{{ $title }}</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>

<body class="flex flex-col min-h-screen">
  <header class="bg-gray-800 text-white p-4 flex justify-between items-center">
    <div class="text-lg font-semibold">{{ $title }}</div>
    <button class="text-white text-xl md:hidden" onclick="toggleMobileMenu()">
      <i class="fas fa-bars"></i>
    </button>
    <nav class="hidden md:flex space-x-4">
      <a href="#top" class="flex items-center space-x-1 text-white hover:underline">
        <i class="fas fa-house text-red-600 w-5 h-5 flex items-center justify-center"></i><span>Top</span>
      </a>
      <a href="#about" class="flex items-center space-x-1 text-white hover:underline">
        <i class="fas fa-file-alt text-blue-500 w-5 h-5 flex items-center justify-center"></i><span>About</span>
      </a>
    </nav>
  </header>
@include('layouts.parts.mobile-menus')
  <div class="flex flex-wrap flex-1">
    @include('layouts.parts.side-menus')
    <main class="flex-1 p-4">
        @yield('content')
    </main>
  </div>
  <footer class="bg-gray-800 text-white text-right py-2 px-4">
    &copy; {{ $year }} {{ $title }}
  </footer>
  <script>
    function toggleMods(el) {
      const list = el.parentElement.querySelector('.mod-list');
      const arrow = el.querySelector('.toggle-arrow');
      const isOpen = list.classList.contains('open');

      if (isOpen) {
        list.style.maxHeight = list.scrollHeight + 'px';
        list.style.visibility = 'visible';
        requestAnimationFrame(() => {
          list.style.maxHeight = '0';
        });
        setTimeout(() => {
          list.style.visibility = 'hidden';
        }, 300);
      } else {
        list.style.visibility = 'visible';
        list.style.maxHeight = list.scrollHeight + 'px';
      }

      list.classList.toggle('open');
      arrow.classList.toggle('rotate');
    }

    function toggleMobileMenu() {
      document.getElementById('mobileMenu').classList.toggle('hidden');
    }

    function toggleMobileMods() {
      const list = document.getElementById('mobileModList');
      const arrow = document.getElementById('mobileModsArrow');
      list.classList.toggle('hidden');
      arrow.classList.toggle('rotate');
    }

    document.addEventListener('DOMContentLoaded', function () {
      const modList = document.querySelector('.mod-list');
      const modArrow = document.querySelector('.toggle-arrow');
      if (modList) {
        modList.classList.remove('open');
        modList.style.maxHeight = '0';
        modList.style.overflow = 'hidden';
        modList.style.visibility = 'hidden';
      }
      if (modArrow) modArrow.classList.remove('rotate');
    });

    function parseVersion(v) {
      return v.split('.').map(n => parseInt(n, 10));
    }

    function compareVersions(a, b) {
      const av = parseVersion(a);
      const bv = parseVersion(b);
      for (let i = 0; i < Math.max(av.length, bv.length); i++) {
        const ai = av[i] || 0;
        const bi = bv[i] || 0;
        if (ai !== bi) return ai - bi;
      }
      return 0;
    }

    let ascending = true;

    function sortTableByVersion() {
      const tbody = document.querySelector("table tbody");
      const rows = Array.from(tbody.querySelectorAll("tr"));
      rows.sort((rowA, rowB) => {
        const verA = rowA.querySelector("td").textContent.trim();
        const verB = rowB.querySelector("td").textContent.trim();
        return ascending ? compareVersions(verA, verB) : compareVersions(verB, verA);
      });
      ascending = !ascending;
      rows.forEach(row => tbody.appendChild(row));
      const up = document.getElementById("versionSortUp");
      const down = document.getElementById("versionSortDown");
      up.classList.toggle("text-gray-800", ascending);
      up.classList.toggle("text-gray-400", !ascending);
      down.classList.toggle("text-gray-800", !ascending);
      down.classList.toggle("text-gray-400", ascending);
    }
  </script>
@stack('scripts')
</body>

</html>
