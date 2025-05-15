<aside class="w-52 bg-gray-100 border-r border-gray-300 p-4 hidden md:block">
  <ul class="space-y-0.5">
    <li>
      <a href="#top" class="flex items-center font-bold hover:bg-gray-200 px-2 py-0.5 rounded">
        <i class="fas fa-house text-red-600 w-5 h-5 flex items-center justify-center"></i><span
          class="ml-2">Top</span>
      </a>
    </li>
    <li>
      <a href="#about" class="flex items-center font-bold hover:bg-gray-200 px-2 py-0.5 rounded">
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
      <ul class="mod-list space-y-1 ml-6 overflow-hidden transition-[max-height] duration-300 ease-in-out"
        style="max-height: 0; visibility: hidden;">
        <li>
          <a href="#mod1"
            class="block bg-gray-200 px-2 py-0.5 rounded hover:bg-gray-300 whitespace-normal break-words text-sm"
            title="Pam's HarvestCraft 2 - Food Extended">
            Pam's HarvestCraft 2 - Food Extended
          </a>
        </li>
        <li>
          <a href="#mod2" class="block bg-gray-200 px-2 py-0.5 rounded hover:bg-gray-300 text-sm">mod_name_2</a>
        </li>
      </ul>
    </li>
  </ul>
</aside>
