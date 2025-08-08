<div>
    <h3 class="text-primary text-center mb-5">Inscription</h3>

    <form action="?page=register-treatment">
        <div class="row mb-3">
            <div class="col">
                <label for="" class="form-label">Nom</label>
                <input type="text" name="last_name" id="" class="form-control">
            </div>
            <div class="col">
                <label for="" class="form-label">Prénoms</label>
                <input type="text" name="first_name" id="" class="form-control">
            </div>
        </div>
        <div class="row mb-3">
            <div class="col">
                <label for="" class="form-label">Sexe</label>
                <select name="sex" id="" class="form-select">
                    <option value="M">Masculin</option>
                    <option value="F">Féminin</option>
                    <option value="N">Non précisé</option>
                </select>
            </div>
            <div class="col">
                <label for="" class="form-label">Email</label>
                <input type="email" name="email" id="" class="form-control">
            </div>
        </div>
        <div class="row mb-3">
            <div class="col">
                <label for="" class="form-label">Mot de passe</label>
                <input type="password" name="password" id="" class="form-control">
            </div>
            <div class="col">
                <label for="" class="form-label">Confirmation mot de passe</label>
                <input type="password" name="confirm-password" id="" class="form-control">
            </div>
        </div>

        <div class="mt-5 d-flex justify-content-center">
            <button type="submit" class="btn w-50 btn-primary">Je m'inscris</button>
        </div>
    </form>
</div>