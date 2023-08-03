<div class="block" style="max-width: 15.6rem">
    <h4>Starting new game</h4>
    <form hx-post="action/start-game.php" hx-target="#main">
        <input type="text" name="leader_name" class="formInput" placeholder="Your name, as a leader."><br />
        <input type="text" name="fortress_name" class="formInput" placeholder="The name of your fortress">
        <p><small>Be thoughtful! If you win, you'll be added to the hall of fame.</small></p>
        <input type="submit" class="formButton" value="Start new game"  />
    </form>
</div>