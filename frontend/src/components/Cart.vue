<script setup>

import DefaultLayout from "../layouts/DefaultLayout.vue";
</script>

<template>


  <H1>Cart ... </H1>
  <DefaultLayout>
    <!-- Страница 1: Каталог товаров -->
    <div class="page-content active" id="catalog">
      <!-- Header -->
      <nav class="navbar navbar-dark bg-dark mb-4">
        <div class="container-fluid">
                    <span class="navbar-brand mb-0 h1">
                        <i class="bi bi-shop"></i> Интернет-магазин
                    </span>
          <div class="d-flex gap-3">
            <a href="#" class="btn btn-outline-light">
              <i class="bi bi-cart3"></i> Корзина (3)
            </a>
            <a href="#" class="btn btn-outline-light">
              <i class="bi bi-person"></i> Войти
            </a>
          </div>
        </div>
      </nav>

      <div class="container-fluid">
        <h2 class="mb-4">Каталог товаров</h2>

<!--        <div class="row">
          &lt;!&ndash; Фильтры &ndash;&gt;
          <div class="col-md-3">
            <div class="card">
              <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-funnel"></i> Фильтры</h5>
              </div>
              <div class="card-body">
                <div class="mb-3">
                  <label class="form-label fw-bold">Категория:</label>
                  <select class="form-select">
                    <option>Все категории</option>
                    <option>Электроника</option>
                    <option>Одежда</option>
                    <option>Книги</option>
                    <option>Спорт</option>
                  </select>
                </div>

                <div class="mb-3">
                  <label class="form-label fw-bold">Цена:</label>
                  <input type="number" class="form-control mb-2" placeholder="От">
                  <input type="number" class="form-control" placeholder="До">
                </div>

                <button class="btn btn-primary w-100 mb-2">Применить</button>
                <button class="btn btn-outline-secondary w-100">Сбросить</button>
              </div>
            </div>
          </div>

          &lt;!&ndash; Товары &ndash;&gt;
          <div class="col-md-9">
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
              &lt;!&ndash; Товар 1 &ndash;&gt;
              <div class="col">
                <div class="card h-100">
                  <div class="product-image">
                    <i class="bi bi-image"></i>
                  </div>
                  <div class="card-body">
                    <h5 class="card-title">Название товара 1</h5>
                    <p class="card-text text-muted">Краткое описание товара для привлечения внимания покупателей...</p>
                    <p class="h4 text-success mb-3">1 999 ₽</p>
                    <button class="btn btn-success w-100">
                      <i class="bi bi-cart-plus"></i> В корзину
                    </button>
                  </div>
                </div>
              </div>

              &lt;!&ndash; Товар 2 &ndash;&gt;
              <div class="col">
                <div class="card h-100">
                  <div class="product-image">
                    <i class="bi bi-image"></i>
                  </div>
                  <div class="card-body">
                    <h5 class="card-title">Название товара 2</h5>
                    <p class="card-text text-muted">Краткое описание товара для привлечения внимания покупателей...</p>
                    <p class="h4 text-success mb-3">2 499 ₽</p>
                    <button class="btn btn-success w-100">
                      <i class="bi bi-cart-plus"></i> В корзину
                    </button>
                  </div>
                </div>
              </div>

              &lt;!&ndash; Товар 3 &ndash;&gt;
              <div class="col">
                <div class="card h-100">
                  <div class="product-image">
                    <i class="bi bi-image"></i>
                  </div>
                  <div class="card-body">
                    <h5 class="card-title">Название товара 3</h5>
                    <p class="card-text text-muted">Краткое описание товара для привлечения внимания покупателей...</p>
                    <p class="h4 text-success mb-3">3 999 ₽</p>
                    <button class="btn btn-success w-100">
                      <i class="bi bi-cart-plus"></i> В корзину
                    </button>
                  </div>
                </div>
              </div>

              &lt;!&ndash; Товар 4 &ndash;&gt;
              <div class="col">
                <div class="card h-100">
                  <div class="product-image">
                    <i class="bi bi-image"></i>
                  </div>
                  <div class="card-body">
                    <h5 class="card-title">Название товара 4</h5>
                    <p class="card-text text-muted">Краткое описание товара для привлечения внимания покупателей...</p>
                    <p class="h4 text-success mb-3">5 500 ₽</p>
                    <button class="btn btn-success w-100">
                      <i class="bi bi-cart-plus"></i> В корзину
                    </button>
                  </div>
                </div>
              </div>

              &lt;!&ndash; Товар 5 &ndash;&gt;
              <div class="col">
                <div class="card h-100">
                  <div class="product-image">
                    <i class="bi bi-image"></i>
                  </div>
                  <div class="card-body">
                    <h5 class="card-title">Название товара 5</h5>
                    <p class="card-text text-muted">Краткое описание товара для привлечения внимания покупателей...</p>
                    <p class="h4 text-success mb-3">7 200 ₽</p>
                    <button class="btn btn-success w-100">
                      <i class="bi bi-cart-plus"></i> В корзину
                    </button>
                  </div>
                </div>
              </div>

              &lt;!&ndash; Товар 6 &ndash;&gt;
              <div class="col">
                <div class="card h-100">
                  <div class="product-image">
                    <i class="bi bi-image"></i>
                  </div>
                  <div class="card-body">
                    <h5 class="card-title">Название товара 6</h5>
                    <p class="card-text text-muted">Краткое описание товара для привлечения внимания покупателей...</p>
                    <p class="h4 text-success mb-3">4 800 ₽</p>
                    <button class="btn btn-success w-100">
                      <i class="bi bi-cart-plus"></i> В корзину
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>-->
      </div>
    </div>

  </DefaultLayout>
</template>

<style scoped>

</style>