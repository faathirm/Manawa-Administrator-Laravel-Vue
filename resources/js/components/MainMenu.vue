<template>
    <aside class="menu">
        <p class="menu-label">
            Account
        </p>
        <ul class="menu-list">
            <li><router-link tag="a" to="/account/admin" class="text-decoration-none"><b-icon icon="account-tie"/> Administrator</router-link></li>
            <li><router-link tag="a" to="/account/farmer" class="text-decoration-none"><b-icon icon="barn"/> Farmer</router-link></li>
            <li><router-link tag="a" to="/account/customer" class="text-decoration-none"><b-icon icon="account-multiple"/> Customer</router-link></li>
        </ul>
        <p class="menu-label">
            Livestock
        </p>
        <ul class="menu-list">
            <li><router-link tag="a" to="/livestock/product" class="text-decoration-none"><b-icon icon="package-variant"/> Product</router-link></li>
            <li><router-link tag="a" to="/livestock/customer" class="text-decoration-none"><b-icon icon="file-account"/> Customer</router-link></li>
        </ul>
        <p class="menu-label">
            Transaction
        </p>
        <ul class="menu-list">
            <li><router-link tag="a" to="/transaction/view" class="text-decoration-none"><b-icon icon="poll"/> Transaction</router-link></li>
            <li><router-link tag="a" to="/transaction/confirmation" class="text-decoration-none"><b-icon icon="check-all"/> Confirmation</router-link></li>
            <li><router-link tag="a" to="/transaction/withdrawal" class="text-decoration-none"><b-icon icon="cash-refund"/> Withdrawal</router-link></li>
        </ul>
        <p class="menu-label">
            Transaction
        </p>
        <ul class="menu-list">
            <li><router-link tag="a" to="/crm/notification" class="text-decoration-none"><b-icon icon="chat"/> Notification</router-link></li>
            <li><router-link tag="a" to="/crm/voucher" class="text-decoration-none"><b-icon icon="ticket-percent"/> Voucher</router-link></li>
        </ul>
    </aside>
</template>
