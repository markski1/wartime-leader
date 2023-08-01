<div class="block" style="max-width: 15.6rem">
    <h4>Starting new game</h4>
    <form hx-post="action/start-game.php" hx-target="#main">
        <input type="text" name="leader_name" class="formInput" placeholder="Your name, as a leader."><br />
        <input type="text" name="fortress_name" class="formInput" placeholder="The name of your fortress"><br />
        <small>If you win you'll be able to submit your fortress to the "hall of fame", so be vaguely thoughtful of the names.</small>
        <input type="submit" class="formButton" value="Start new game"  />
    </form>
</div>