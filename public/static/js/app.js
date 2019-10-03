'use strict';

document.addEventListener('DOMContentLoaded', function () {

    // Modals

    let rootEl = document.documentElement;
    let $modals = getAll('.modal');
    let $modalButtons = getAll('.modal-button');
    let $modalCloses = getAll('.modal-background, .modal-close, .modal-card-head .delete');

    if ($modalButtons.length > 0) {
        $modalButtons.forEach(function ($el) {
            $el.addEventListener('click', function () {
                var target = $el.dataset.target;
                var $target = document.getElementById(target);
                rootEl.classList.add('is-clipped');
                $target.classList.add('is-active');
            });
        });
    }

    if ($modalCloses.length > 0) {
        $modalCloses.forEach(function ($el) {
            $el.addEventListener('click', function () {
                closeModals();
            });
        });
    }

    document.addEventListener('keydown', function (event) {
        let e = event || window.event;
        if (e.keyCode === 27) {
            closeModals();
        }
    });

    function closeModals() {
        rootEl.classList.remove('is-clipped');
        $modals.forEach(function ($el) {
            $el.classList.remove('is-active');
        });
    }

    // Functions

    function getAll(selector) {
        return Array.prototype.slice.call(document.querySelectorAll(selector), 0);
    }

});

function loadModal(link) {
    console.log("loading modal for", link);

    if (!link || !link.dataset || !link.dataset.target) {
        console.log("error: couldn't find modal for link", link);
        return false;
    }

    const modal = document.getElementById(link.dataset.target);
    if (!modal) {
        console.log("error: couldn't find modal for link", link);
        return false;
    }

    const wrapper = modal.querySelector(".recaptcha-wrapper")
    if (!wrapper) {
        console.log("error: couldn't find wrapper for modal", modal);
        return false;
    }

    const id = grecaptcha.render(wrapper, { "sitekey" : "6Lcsl7sUAAAAAGp0LZHraq9XY9RTQDSJK-ZwKJWa" });
    console.log("rendered recaptcha", id);

    return true;
}
