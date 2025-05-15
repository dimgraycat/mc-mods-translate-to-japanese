<aside class="w-100 bg-gray-100 border-r border-gray-300 p-4 hidden md:block">
  <ul class="space-y-0.5">
    <li>
      <a href="index.html#top" class="flex items-center font-bold hover:bg-gray-200 px-2 py-0.5 rounded">
        <i class="fas fa-house text-red-600 w-5 h-5 flex items-center justify-center"></i><span
          class="ml-2">Top</span>
      </a>
    </li>
    <li>
      <a href="index.html#about" class="flex items-center font-bold hover:bg-gray-200 px-2 py-0.5 rounded">
        <i class="fas fa-file-alt text-blue-500 w-5 h-5 flex items-center justify-center"></i><span
          class="ml-2">About</span>
      </a>
    </li>
    <li>
      <div class="flex items-center justify-between font-bold hover:bg-gray-200 px-2 py-0.5 rounded cursor-pointer"
        onclick="toggleMods(this)">
        <div class="flex items-center">
          <i class="fas fa-folder text-orange-400 w-5 h-5 flex items-center justify-center"></i><span
            class="ml-2">Mods</span>
        </div>
        <i class="fas fa-chevron-right toggle-arrow"></i>
      </div>
      <ul id="sideModList" class="mod-list space-y-1 ml-6 overflow-hidden transition-[max-height] duration-300 ease-in-out"
        style="max-height: 0; visibility: hidden;">
        <!-- MODリストはここにJSから動的に挿入されます -->
      </ul>
    </li>
  </ul>
</aside>
