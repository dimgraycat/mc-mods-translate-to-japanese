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
  <div class="js-mods-data" style="display:none" data-mods='{{ getJsonFromFile(public_path("index.json")) }}'></div>
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
  <div class="flex flex-wrap flex-1">
    @include('layouts.parts.side-menus')
    <main class="flex-1 p-4">
        @yield('content')
    </main>
  </div>
  <footer class="bg-gray-800 text-white text-right py-2 px-4">
    &copy; {{ $year }} {{ $title }}
  </footer>
  <script><!--
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

    function getQueryParam(param) {
      const urlParams = new URLSearchParams(window.location.search);
      return urlParams.get(param);
    }

    function generateSideMenuMods() {
      const modsDataElement = document.querySelector('.js-mods-data');
      const sideModListElement = document.getElementById('sideModList');

      if (!modsDataElement || !sideModListElement) {
        console.error('Required elements for side menu generation not found.');
        return;
      }

      try {
        const modsJsonString = modsDataElement.dataset.mods;
        const mods = JSON.parse(modsJsonString);

        if (!Array.isArray(mods)) {
          console.error('Parsed mods data is not an array.');
          return;
        }

        sideModListElement.innerHTML = '';

        mods.forEach(mod => {
          if (mod && mod.name) {
            const listItem = document.createElement('li');
            const link = document.createElement('a');
            const modId = mod.id;

            link.href = `mods.html?id=${modId}`;
            link.className = 'block bg-gray-200 px-2 py-0.5 rounded hover:bg-gray-300 whitespace-normal break-words text-sm';
            link.title = mod.name;
            link.textContent = mod.name;

            listItem.appendChild(link);
            sideModListElement.appendChild(listItem);
          }
        });
      } catch (error) {
        console.error('Error parsing mods data or generating side menu:', error);
      }
    }

    function openModsList() {
      const list = document.getElementById('sideModList');
      const toggleButton = list.previousElementSibling;
      if (!toggleButton) return;
      const arrow = toggleButton.querySelector('.toggle-arrow');

      if (list && !list.classList.contains('open')) {
        list.style.visibility = 'visible';
        list.style.maxHeight = list.scrollHeight + 'px';
        list.classList.add('open');
        if (arrow) arrow.classList.add('rotate');
      }
    }

    document.addEventListener('DOMContentLoaded', function () {
      generateSideMenuMods();

      let modSectionOpenedByParam = false;
      const targetModId = getQueryParam('id');
      const sideModList = document.getElementById('sideModList');

      if (targetModId && sideModList) {
        const links = sideModList.querySelectorAll('a');
        links.forEach(link => {
          try {
            const linkUrl = new URL(link.href);
            const linkModId = linkUrl.searchParams.get('id');
            if (linkModId === targetModId) {
              link.classList.add('bg-blue-200', 'font-semibold');
              openModsList();
              modSectionOpenedByParam = true;
            }
          } catch (e) {
          }
        });
      }

      if (!modSectionOpenedByParam) {
        const modToggleButton = sideModList ? sideModList.previousElementSibling : null;
        const modArrow = modToggleButton ? modToggleButton.querySelector('.toggle-arrow') : null;

        if (sideModList) {
          sideModList.classList.remove('open');
          sideModList.style.maxHeight = '0';
          sideModList.style.overflow = 'hidden';
          sideModList.style.visibility = 'hidden';
        }
        if (modArrow) modArrow.classList.remove('rotate');
      }
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
  --></script>
@stack('scripts')
</body>

</html>
