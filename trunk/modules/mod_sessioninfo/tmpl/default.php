<p class="greeting">
    <a href="<?php echo $profileLink ?>">
        <?php echo $greetingMessage ?>
    </a>
</p>
<input type="button"
       value="<?php echo $sessionCloseMessage ?>"
       name="closesession"
       class="closesession"
       onclick="logout()"
/>
