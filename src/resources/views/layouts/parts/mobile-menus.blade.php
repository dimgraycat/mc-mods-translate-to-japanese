<div id="mobileMenu" class="bg-gray-700 text-white p-4 md:hidden hidden">
  <a href="#top" class="flex items-center py-2 px-2 rounded hover:bg-gray-600">
    <i class="fas fa-house text-red-600 w-5 h-5 flex items-center justify-center"></i><span class="ml-2">Top</span>
  </a>
  <a href="#about" class="flex items-center py-2 px-2 rounded hover:bg-gray-600">
    <i class="fas fa-file-alt text-blue-500 w-5 h-5 flex items-center justify-center"></i><span
      class="ml-2">About</span>
  </a>
  <div class="flex items-center justify-between cursor-pointer py-2 px-2 hover:bg-gray-600"
    onclick="toggleMobileMods()">
    <div class="flex items-center">
      <i class="fas fa-folder text-orange-400 w-5 h-5 flex items-center justify-center"></i><span
        class="ml-2">Mods</span>
    </div>
    <i id="mobileModsArrow" class="fas fa-chevron-right"></i>
  </div>
  <div id="mobileModList" class="ml-6 hidden transition-all duration-300 ease-in-out overflow-hidden">
    <a href="#mod1" class="block py-1">Pam's HarvestCraft 2 - Food Extended</a>
    <a href="#mod2" class="block py-1">mod_name_2</a>
  </div>
</div>