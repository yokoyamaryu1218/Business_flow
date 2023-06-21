function updateActiveMenu() {
    // スクロール位置を取得
    var scrollPosition = window.pageYOffset;

    // 各セクションの位置を取得
    var section1Position = document.getElementById("section-1").offsetTop;
    var section2Position = document.getElementById("section-2").offsetTop;
    var section3Position, section4Position;
    
    // 別ファイルの特定の部分を削除するための条件文を追加
    if (document.getElementById("section-3")) {
        section3Position = document.getElementById("section-3").offsetTop;
    }
    
    if (document.getElementById("section-4")) {
        section4Position = document.getElementById("section-4").offsetTop;
    }

    // すべてのアクティブなクラスを削除
    var menuSection1 = document.getElementById("menu-section-1");
    var menuSection2 = document.getElementById("menu-section-2");
    var menuSection3, menuSection4;
    var anchor1 = menuSection1.querySelector("a");
    var anchor2 = menuSection2.querySelector("a");
    var anchor3, anchor4;
    var img1 = menuSection1.querySelector("img");
    var img2 = menuSection2.querySelector("img");
    var img3, img4;
    menuSection1.classList.remove("bg-blue-500", "text-white");
    menuSection2.classList.remove("bg-blue-500", "text-white");
    anchor1.classList.remove("text-white", "text-red-500");
    anchor2.classList.remove("text-white", "text-red-500");
    img1.style.filter = '';
    img2.style.filter = '';
    anchor1.classList.add("text-red-500");
    anchor2.classList.add("text-red-500");

    // スクロール位置に基づいてアクティブなメニューをハイライト
    if (scrollPosition >= section1Position && scrollPosition < section2Position) {
        menuSection1.classList.add("bg-blue-500", "text-white");
        anchor1.classList.add("text-white");
        anchor1.classList.remove("text-red-500");
        img1.style.filter = 'invert(1) brightness(100)'; // ここで画像の色を白に変更する
    } else if (scrollPosition >= section2Position && scrollPosition < section3Position) {
        menuSection2.classList.add("bg-blue-500", "text-white");
        anchor2.classList.add("text-white");
        anchor2.classList.remove("text-red-500");
        img2.style.filter = 'invert(1) brightness(100)'; // ここで画像の色を白に変更する
    } else if (scrollPosition >= section3Position && scrollPosition < section4Position) {
        menuSection3.classList.add("bg-blue-500", "text-white");
        anchor3.classList.add("text-white");
        anchor3.classList.remove("text-red-500");
        img3.style.filter = 'invert(1) brightness(100)'; // ここで画像の色を白に変更する
    }

    // 追加のセクションに対しても同様の処理を行う
    if (section4Position && scrollPosition >= section4Position) {
        menuSection4.classList.add("bg-blue-500", "text-white");
        anchor4.classList.add("text-white");
        anchor4.classList.remove("text-red-500");
        img4.style.filter = 'invert(1) brightness(100)'; // ここで画像の色を白に変更する
    }
}

// スクロール時にアクティブなメニューを更新
window.addEventListener("scroll", updateActiveMenu);

// ページの読み込み時にアクティブなメニューを更新
document.addEventListener("DOMContentLoaded", updateActiveMenu);
