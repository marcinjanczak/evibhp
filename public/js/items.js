    function openEditModal(id, nazwa, typ, rozmiar, ilosc) {
        document.getElementById('editModal').classList.add('active');
        document.getElementById('editIdPrzedmiot').value = id;
        document.getElementById('editNazwa').value = nazwa;
        document.getElementById('editTyp').value = typ;
        document.getElementById('editRozmiar').value = rozmiar;
        document.getElementById('editIlosc').value = ilosc;
        document.getElementById('editForm').action = '/items/' + id;
    }
    function closeEditModal() {
        document.getElementById('editModal').classList.remove('active');
    }