function updateActiveMenu() {
    // スクロール位置を取得
    var scrollPosition = window.pageYOffset;

    // 各セクションの位置を取得
    var section1Position = document.getElementById("section-1").offsetTop;
    var section2Position = document.getElementById("section-2").offsetTop;

    // すべてのアクティブなクラスを削除
    var menuSection1 = document.getElementById("menu-section-1");
    var menuSection2 = document.getElementById("menu-section-2");
    var anchor1 = menuSection1.querySelector("a");
    var anchor2 = menuSection2.querySelector("a");
    var img1 = menuSection1.querySelector("img");
    var img2 = menuSection2.querySelector("img");
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
    } else if (scrollPosition >= section2Position) {
        menuSection2.classList.add("bg-blue-500", "text-white");
        anchor2.classList.add("text-white");
        anchor2.classList.remove("text-red-500");
        img2.style.filter = 'invert(1) brightness(100)'; // ここで画像の色を白に変更する
    }
}

// スクロール時にアクティブなメニューを更新
window.addEventListener("scroll", updateActiveMenu);

// ページの読み込み時にアクティブなメニューを更新
document.addEventListener("DOMContentLoaded", updateActiveMenu);