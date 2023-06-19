class BreadcrumbGenerator {
    constructor(containerId) {
        this.container = document.getElementById(containerId);
        this.breadcrumbs = [];
    }

    addBreadcrumb(label, url = '') {
        this.breadcrumbs.push({ label, url });
    }

    generateBreadcrumbs() {
        this.container.innerHTML = '';

        this.breadcrumbs.forEach((breadcrumb, index) => {
            const li = document.createElement('li');

            if (index === this.breadcrumbs.length - 1) {
                li.setAttribute('aria-current', 'page');
                const div = document.createElement('div');
                div.classList.add('flex', 'items-center');

                if (breadcrumb.label === '人員管理') {
                    const span = document.createElement('span');
                    span.classList.add('ml-1', 'text-base', 'font-semibold', 'md:ml-2');
                    span.textContent = breadcrumb.label;
                    div.appendChild(span);
                } else {
                    const svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
                    svg.setAttribute('aria-hidden', 'true');
                    svg.classList.add('w-6', 'h-6', 'text-gray-400');
                    svg.setAttribute('fill', 'currentColor');
                    svg.setAttribute('viewBox', '0 0 20 20');
                    svg.setAttribute('xmlns', 'http://www.w3.org/2000/svg');
                    const path = document.createElementNS('http://www.w3.org/2000/svg', 'path');
                    path.setAttribute('fill-rule', 'evenodd');
                    path.setAttribute('d', 'M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z');
                    path.setAttribute('clip-rule', 'evenodd');
                    svg.appendChild(path);
                    div.appendChild(svg);

                    const span = document.createElement('span');
                    span.classList.add('ml-1', 'text-base', 'font-semibold', 'md:ml-2');
                    span.textContent = breadcrumb.label;
                    div.appendChild(span);
                }

                li.appendChild(div);
            } else {
                const a = document.createElement('a');
                a.setAttribute('href', breadcrumb.url);
                a.classList.add('inline-flex', 'items-center', 'text-base', 'font-medium', 'text-blue-700', 'hover:text-blue-600', 'dark:text-blue-400', 'dark:hover:text-white');
                a.textContent = breadcrumb.label;
                li.appendChild(a);
            }

            this.container.appendChild(li);
        });
    }
}

document.addEventListener('DOMContentLoaded', function () {
    const breadcrumb = document.getElementById('breadcrumb');
    const userIndexUrl = breadcrumb.dataset.userIndexUrl;
    const pageTitle = breadcrumb.dataset.title;

    const breadcrumbGenerator = new BreadcrumbGenerator('breadcrumb');

    if (pageTitle !== '人員管理') {
        breadcrumbGenerator.addBreadcrumb('人員管理', userIndexUrl);
    }
    breadcrumbGenerator.addBreadcrumb(pageTitle, '');

    breadcrumbGenerator.generateBreadcrumbs();
});
