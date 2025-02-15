// choices js buat select box
import "choices.js/public/assets/styles/choices.css";
import Choices from 'choices.js';

document.addEventListener("DOMContentLoaded", () => {
    initChoicesToSelect();
});

function initChoicesToSelect() {
    const lokasiBarang = document.querySelector("#lokasiBarang");
    const choices = new Choices(lokasiBarang, {
        placeholder: true,
        placeholderValue: "Pilih Lokasi Barang",
    });
}
