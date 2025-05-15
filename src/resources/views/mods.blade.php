@extends('layouts.app')
@section('content')
<h2 id="mod-title" class="text-lg font-semibold mt-4 mb-4">MOD情報を読み込み中...</h2>
<div id="mod-supported-versions-container" class="flex items-center flex-wrap gap-1 mb-1 text-sm" style="display: none;">
  <span class="text-sm text-gray-600">対応バージョン:</span>
  <span id="mod-versions-list">
    <!-- バージョンタグはここにJSで挿入されます -->
  </span>
</div>
<div class="overflow-x-auto mt-2 max-w-5xl mx-auto">
  <table class="min-w-full divide-y divide-gray-300 text-sm">
    <thead class="bg-gray-100">
      <tr>
        <th scope="col" class="px-4 py-2 w-32 text-right font-semibold text-gray-700">バージョン
        </th>
        <th scope="col" class="px-4 py-2 text-left font-semibold text-gray-700">ファイル名</th>
        <th scope="col" class="px-4 py-2 text-left font-semibold text-gray-700">SHA1</th>
        <th scope="col" class="px-4 py-2 text-left font-semibold text-gray-700">サイズ</th>
      </tr>
    </thead>
    <tbody id="mod-downloads-tbody" class="divide-y divide-gray-200">
      <!-- ダウンロード情報はここにJSで挿入されます -->
      <tr>
        <td colspan="4" class="px-4 py-2 text-center text-gray-500">MOD情報を読み込み中...</td>
      </tr>
    </tbody>
  </table>
</div>
@endsection

@push('scripts')
<script><!--
document.addEventListener('DOMContentLoaded', function () {
    const modsDataElement = document.querySelector('.js-mods-data');
    const modTitleElement = document.getElementById('mod-title');
    const modVersionsContainerElement = document.getElementById('mod-supported-versions-container');
    const modVersionsListElement = document.getElementById('mod-versions-list');
    const modDownloadsTbodyElement = document.getElementById('mod-downloads-tbody');

    function getQueryParam(param) {
      const urlParams = new URLSearchParams(window.location.search);
      return urlParams.get(param);
    }

    const targetModId = getQueryParam('id');

    if (!modsDataElement || !modTitleElement || !modVersionsListElement || !modDownloadsTbodyElement || !modVersionsContainerElement) {
        console.error('Required page elements not found.');
        if(modTitleElement) modTitleElement.textContent = 'ページの読み込みエラー';
        if(modDownloadsTbodyElement) modDownloadsTbodyElement.innerHTML = '<tr><td colspan="4" class="px-4 py-2 text-center text-red-500">ページの読み込みエラーが発生しました。</td></tr>';
        return;
    }

    if (!targetModId) {
        modTitleElement.textContent = 'MODが指定されていません';
        modDownloadsTbodyElement.innerHTML = '<tr><td colspan="4" class="px-4 py-2 text-center text-gray-500">表示するMODが指定されていません。</td></tr>';
        return;
    }

    try {
        const modsJsonString = modsDataElement.dataset.mods;
        const allMods = JSON.parse(modsJsonString);
        const currentMod = allMods.find(mod => mod.id === targetModId);

        if (currentMod) {
            modTitleElement.textContent = currentMod.name;
            document.title = `${currentMod.name} | ${document.title.split('|').pop().trim()}`; // Update page title

            // 対応バージョン表示
            modVersionsListElement.innerHTML = '';
            let displayableVersions = new Set();

            if (currentMod.versions && currentMod.versions.length > 0 && packFormatData.length > 0) {
                currentMod.versions.forEach(actualVersion => {
                    packFormatData.forEach(pf => {
                        // actualVersion が pf.from と pf.to の範囲内にあるかチェック
                        // packformat.php の from/to が文字列であることを想定
                        if (compareVersions(actualVersion, pf.from) >= 0 && compareVersions(actualVersion, pf.to) <= 0) {
                            displayableVersions.add(pf.from);
                            displayableVersions.add(pf.to);
                        }
                    });
                });
            }

            if (displayableVersions.size > 0) {
                const sortedDisplayVersions = Array.from(displayableVersions).sort(compareVersions);
                sortedDisplayVersions.forEach(version => {
                    const versionSpan = document.createElement('span');
                    versionSpan.className = 'inline-block bg-blue-100 text-blue-800 text-xs font-semibold mr-1 px-2.5 py-0.5 rounded';
                    versionSpan.textContent = version;
                    modVersionsListElement.appendChild(versionSpan);
                });
                modVersionsContainerElement.style.display = 'flex';
            } else if (currentMod.versions && currentMod.versions.length > 0) {
                // フォールバック: packFormatData とのマッチングがうまくいかなかった場合など
                // currentMod.versions をそのまま表示 (ソートしておく)
                currentMod.versions.sort(compareVersions).forEach(version => {
                    const versionSpan = document.createElement('span');
                    versionSpan.className = 'inline-block bg-blue-100 text-blue-800 text-xs font-semibold mr-1 px-2.5 py-0.5 rounded';
                    versionSpan.textContent = version;
                    modVersionsListElement.appendChild(versionSpan);
                });
                modVersionsContainerElement.style.display = 'flex';
            } else {
                modVersionsContainerElement.style.display = 'none';
            }

            modDownloadsTbodyElement.innerHTML = '';
            if (currentMod.links && Object.keys(currentMod.links).length > 0) {
                const sortedVersions = Object.keys(currentMod.links).sort((a, b) => compareVersions(b, a));

                sortedVersions.forEach(versionKey => {
                    const linkData = currentMod.links[versionKey];
                    const row = modDownloadsTbodyElement.insertRow();

                    let displayVersion = versionKey; // デフォルトは元のキー
                    // packFormatData は layouts/app.blade.php で定義済み
                    if (typeof packFormatData !== 'undefined' && packFormatData.length > 0) {
                        for (const pf of packFormatData) {
                            // versionKey が pf.from と pf.to の範囲内にあるかチェック
                            if (compareVersions(versionKey, pf.from) >= 0 && compareVersions(versionKey, pf.to) <= 0) {
                                if (pf.from === pf.to) {
                                    displayVersion = pf.from; // from と to が同じなら単一バージョン表示
                                } else {
                                    displayVersion = `${pf.from} - ${pf.to}`;
                                }
                                break; // 最初に見つかった範囲を採用
                            }
                        }
                    }
                    row.insertCell().outerHTML = `<td class="px-4 py-2 text-gray-900 text-right align-top">${displayVersion}</td>`;
                    const fileCell = row.insertCell();
                    fileCell.className = 'px-4 py-2 align-top';
                    fileCell.innerHTML = `<a href="${linkData.url}" class="text-blue-600 hover:underline" download><i class="fas fa-download mr-1"></i>${linkData.file}</a>`;
                    const sha1Cell = row.insertCell();
                    sha1Cell.className = 'px-4 py-2 align-top';
                    sha1Cell.innerHTML = `<span class="font-mono text-xs break-all">${linkData.sha1 || 'N/A'}</span>`;
                    const sizeCell = row.insertCell();
                    sizeCell.textContent = linkData.size || 'N/A';
                    sizeCell.className = 'px-4 py-2 text-right align-top';
                });
            } else {
                modDownloadsTbodyElement.innerHTML = '<tr><td colspan="4" class="px-4 py-2 text-center text-gray-500">利用可能なダウンロードはありません。</td></tr>';
            }
        } else {
            modTitleElement.textContent = '指定されたMODが見つかりません';
            modDownloadsTbodyElement.innerHTML = '<tr><td colspan="4" class="px-4 py-2 text-center text-gray-500">指定されたMODは見つかりませんでした。</td></tr>';
            modVersionsContainerElement.style.display = 'none';
        }
    } catch (error) {
        console.error('Error processing mod data:', error);
        modTitleElement.textContent = 'MOD情報の表示エラー';
        modDownloadsTbodyElement.innerHTML = '<tr><td colspan="4" class="px-4 py-2 text-center text-red-500">MOD情報の表示中にエラーが発生しました。</td></tr>';
        modVersionsContainerElement.style.display = 'none';
    }
});
--></script>
@endpush