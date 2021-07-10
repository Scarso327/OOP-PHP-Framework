<div>
    <div>
        <h1>Settings</h1>
        <p>Manage your account settings, and set up 3rd party integrations.</p>
    </div>
    <div id="settingsBody">
        <ul class="tab-links">
            <li class=<?=(strtolower($this->tab) == "general") ? "active" : "";?>><a href="settings">Overview</a></li>
            <li class=<?=(strtolower($this->tab) == "name") ? "active" : "";?>><a href="settings/name">Display Name</a></li>
            <li class=<?=(strtolower($this->tab) == "intergrations") ? "active" : "";?>><a href="settings/intergrations">Intergrations</a></li>
        </ul>
        <div>
            <div>
                <h2><?=ucwords($this->tab);?> Settings</h2>
            </div>
            <?php
            if (count($this->messages) > 0) {
                foreach ($this->messages as $message) {
                    echo $message."</br>";
                }
            }

            echo $this->page;
            ?>
        </div>
    </div>
</div>