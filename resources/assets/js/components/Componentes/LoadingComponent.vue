<template>
    <div class="info-center">
        <div style="margin-top:80px; margin-bottom:80px;">
            <span class="loader"></span>
        </div>
    </div>
</template>

<script>
export default {};
</script>

<style scoped>
.loader{
    position: relative;
    font-size: 48px;
    letter-spacing: 2px;
  }
  .loader:before {
    content: "Cargando.";
    color: #153157;
  }
  .loader:after {
    content: "";
    width: 20px;
    height: 20px;
    background-color: #EC008C;
    border-radius: 50%;
    position: absolute;
    inset: 0;
    margin: auto;
    top: -70px;
    animation: motion 3s ease-in-out infinite;
  }

  @keyframes motion {
    0%, 50%, 100% {
      transform: translateX(0) scale(1);
    }
    25% {
      transform: translateX(-100px) scale(0.3);
    }
    75% {
      transform: translateX(100px) scale(0.3);
    }
  }
  .info-center{
        display: flex;
        justify-content: center;
        width: 100% !important;
    }
</style>
