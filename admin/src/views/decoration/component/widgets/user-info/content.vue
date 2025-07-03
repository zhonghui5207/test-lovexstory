<template>
    <div class="user-info flex items-center px-[25px]">
        <img src="./images/default_avatar.png" class="w-[60px] h-[60px]" alt="" />
        <div class="ml-[10px]">
            <div class="text-[18px]">小贝</div>
            <div class="form-tips">学号：123456 复制</div>
        </div>
    </div>
</template>
<script lang="ts" setup></script>

<style lang="scss" scoped>
.user-info {
    background: url(./images/my_topbg.png);
    height: 115px;
    background-position: bottom;
    background-size: 100% auto;
}
</style>
