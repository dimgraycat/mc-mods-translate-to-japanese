@extends('layouts.app')
@section('content')
<h1 id="top" class="text-xl font-bold mb-2">Top</h1>
<p class="mb-4">ここに管理概要や最近の更新を記載できます。</p>

<h1 id="about" class="text-xl font-bold mt-6">About</h1>
<p>このページはMOD翻訳の進捗と状況を管理するためのインデックスです。</p>

<h2 id="mod1" class="text-lg font-semibold mt-4 mb-2">Pam's HarvestCraft 2 - Food Extended</h2>
<div class="flex items-center flex-wrap gap-1 mb-1 text-sm">
  <span class="text-sm text-gray-600">対応バージョン:</span>
  <span
    class="inline-block bg-blue-100 text-blue-800 text-xs font-semibold mr-1 px-2.5 py-0.5 rounded">1.20.1</span>
  <span
    class="inline-block bg-blue-100 text-blue-800 text-xs font-semibold mr-1 px-2.5 py-0.5 rounded">1.21</span>
  <span
    class="inline-block bg-blue-100 text-blue-800 text-xs font-semibold mr-1 px-2.5 py-0.5 rounded">1.21.1</span>
</div>
<div class="overflow-x-auto mt-2">
  <table class="min-w-full divide-y divide-gray-300 text-sm">
    <thead class="bg-gray-100">
      <tr>
        <th scope="col" class="px-4 py-2 w-32 text-left font-semibold text-gray-700">
          <button onclick="sortTableByVersion()" title="バージョンでソート"
            class="flex items-center gap-1 px-2 py-1 rounded hover:bg-gray-200">
            <span>バージョン</span>
            <span class="relative w-3 h-4 ml-1">
              <i id="versionSortUp" class="fas fa-sort-up text-gray-400 absolute top-0 left-0"></i>
              <i id="versionSortDown" class="fas fa-sort-down text-gray-400 absolute bottom-0 left-0"></i>
            </span>
          </button>
        </th>
        <th scope="col" class="px-4 py-2 text-left font-semibold text-gray-700">ファイル名</th>
        <th scope="col" class="px-4 py-2 text-left font-semibold text-gray-700">サイズ</th>
        <th scope="col" class="px-4 py-2 text-left font-semibold text-gray-700">SHA1</th>
      </tr>
    </thead>
    <tbody class="divide-y divide-gray-200">
      <tr>
        <td class="px-4 py-2 font-bold text-gray-900">1.20.1</td>
        <td class="px-4 py-2">
          <a href="#" class="text-blue-600 hover:underline">
            <i class="fas fa-download mr-1"></i>harvestcraft-1.20.1-ja.zip
          </a>
        </td>
        <td class="px-4 py-2">128 KB</td>
        <td class="px-4 py-2 font-mono text-xs break-all">dcba4321hgfe8765lkji2109ponm6543tsrq0987</td>
      </tr>
      <tr>
        <td class="px-4 py-2 font-bold text-gray-900">1.21</td>
        <td class="px-4 py-2">
          <a href="#" class="text-blue-600 hover:underline">
            <i class="fas fa-download mr-1"></i>harvestcraft-1.21-ja.zip
          </a>
        </td>
        <td class="px-4 py-2">128 KB</td>
        <td class="px-4 py-2 font-mono text-xs break-all">dcba4321hgfe8765lkji2109ponm6543tsrq0987</td>
      </tr>
      <tr>
        <td class="px-4 py-2 font-bold text-gray-900">1.21.1</td>
        <td class="px-4 py-2">
          <a href="#" class="text-blue-600 hover:underline">
            <i class="fas fa-download mr-1"></i>harvestcraft-1.21.1-ja.zip
          </a>
        </td>
        <td class="px-4 py-2">130 KB</td>
        <td class="px-4 py-2 font-mono text-xs break-all">1122aabbccddeeff00112233445566778899aabb</td>
      </tr>
    </tbody>
  </table>
</div>
@endsection