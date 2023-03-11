<template>
  <ul id="nav" class="nav p-3 mb-4">
    <li class="nav-item mt-auto">
      <!--      <a class="nav-link active" aria-current="page" href="#">Active</a>-->
      <router-link
          id="logo"
          :to="{name: 'summary.manage'}"
      >
        Главная страница
      </router-link>
    </li>
    <li class="nav-item ms-4 mt-auto">
      <router-link
          :to="{name: 'summary.add'}"
      >
        Новое резюме
      </router-link>
    </li>
  </ul>
</template>

<script>
export default {
  name: "TheNavigation"
}
</script>

<style lang="scss">
#logo {
  font-size: 1.2rem;
}

#nav {
  background: darkslategrey;
}

#nav .my-active-link {
  color: lightcoral;
}

#nav a {
  color: white;
  text-decoration: none;
}

#nav a:hover {
  color: darkslategrey;
  text-decoration: none;
}
</style>