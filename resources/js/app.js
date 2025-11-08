import './bootstrap';

class MiniPlayer {
    constructor() {
        this.audio = document.getElementById('audioElement');
        this.player = document.getElementById('audioPlayer');
        if (!this.audio || !this.player) {
            return;
        }

        this.artworkEl = document.getElementById('playerArtwork');
        this.trackNameEl = document.getElementById('playerTrackName');
        this.artistNameEl = document.getElementById('playerArtistName');
        this.metaEl = document.getElementById('playerMeta');
        this.playPauseButton = document.getElementById('playerPlayPause');
        this.playIcon = this.playPauseButton?.querySelector('.play-icon');
        this.pauseIcon = this.playPauseButton?.querySelector('.pause-icon');
        this.progressInput = document.getElementById('playerProgress');
        this.currentTimeEl = document.getElementById('playerCurrentTime');
        this.durationEl = document.getElementById('playerDuration');
        this.volumeInput = document.getElementById('playerVolume');
        this.muteButton = document.getElementById('playerMute');
        this.volumeOnIcon = this.muteButton?.querySelector('.volume-on');
        this.volumeOffIcon = this.muteButton?.querySelector('.volume-off');
        this.closeButton = document.getElementById('playerClose');
        this.rewindButton = document.getElementById('playerRewind');
        this.forwardButton = document.getElementById('playerForward');

        this.isMuted = false;
        this.currentTrack = null;

        this.bindEvents();
    }

    bindEvents() {
        if (!this.audio) {
            return;
        }

        this.playPauseButton?.addEventListener('click', () => {
            if (this.audio.paused) {
                this.audio.play();
            } else {
                this.audio.pause();
            }
        });

        this.progressInput?.addEventListener('input', (event) => {
            if (!this.audio.duration) {
                return;
            }
            const value = Number(event.target.value);
            const seekTime = (value / 100) * this.audio.duration;
            this.audio.currentTime = seekTime;
        });

        this.volumeInput?.addEventListener('input', (event) => {
            const value = Number(event.target.value);
            this.audio.volume = value;
            this.updateVolumeIcons(value);
            this.isMuted = value === 0;
        });

        this.muteButton?.addEventListener('click', () => {
            this.isMuted = !this.isMuted;
            if (this.isMuted) {
                this.previousVolume = this.audio.volume;
                this.audio.volume = 0;
                if (this.volumeInput) {
                    this.volumeInput.value = 0;
                }
            } else {
                this.audio.volume = this.previousVolume ?? 0.8;
                if (this.volumeInput) {
                    this.volumeInput.value = this.audio.volume.toString();
                }
            }
            this.updateVolumeIcons(this.audio.volume);
        });

        this.closeButton?.addEventListener('click', () => {
            this.audio.pause();
            this.audio.currentTime = 0;
            this.player.classList.add('hidden');
        });

        this.rewindButton?.addEventListener('click', () => {
            if (!this.audio) {
                return;
            }
            this.audio.currentTime = Math.max(0, this.audio.currentTime - 10);
        });

        this.forwardButton?.addEventListener('click', () => {
            if (!this.audio || !this.audio.duration) {
                return;
            }
            this.audio.currentTime = Math.min(this.audio.duration, this.audio.currentTime + 10);
        });

        this.audio.addEventListener('timeupdate', () => {
            this.updateProgress();
        });

        this.audio.addEventListener('loadedmetadata', () => {
            this.updateDuration();
        });

        this.audio.addEventListener('play', () => {
            this.togglePlayState(true);
        });

        this.audio.addEventListener('pause', () => {
            this.togglePlayState(false);
        });

        this.audio.addEventListener('ended', () => {
            this.togglePlayState(false);
            this.audio.currentTime = 0;
            this.updateProgress();
        });
    }

    updateProgress() {
        if (!this.audio || !this.audio.duration || !this.progressInput) {
            return;
        }
        const progress = (this.audio.currentTime / this.audio.duration) * 100;
        this.progressInput.value = progress.toString();
        if (this.currentTimeEl) {
            this.currentTimeEl.textContent = this.formatTime(this.audio.currentTime);
        }
    }

    updateDuration() {
        if (!this.audio) {
            return;
        }

        if (this.durationEl) {
            const duration = this.audio.duration || 0;
            this.durationEl.textContent = this.formatTime(duration);
        }
    }

    updateVolumeIcons(volume) {
        if (this.volumeOnIcon && this.volumeOffIcon) {
            const isSilent = volume === 0;
            this.volumeOnIcon.classList.toggle('hidden', isSilent);
            this.volumeOffIcon.classList.toggle('hidden', !isSilent);
        }
    }

    togglePlayState(isPlaying) {
        if (this.playIcon && this.pauseIcon) {
            this.playIcon.classList.toggle('hidden', isPlaying);
            this.pauseIcon.classList.toggle('hidden', !isPlaying);
        }
        this.playPauseButton?.setAttribute('aria-label', isPlaying ? 'Pause' : 'Play');
    }

    formatTime(seconds) {
        if (!isFinite(seconds)) {
            return '0:00';
        }
        const minutes = Math.floor(seconds / 60);
        const secs = Math.floor(seconds % 60).toString().padStart(2, '0');
        return `${minutes}:${secs}`;
    }

    play({ url, trackName, artistName, artworkUrl, meta = {} }) {
        if (!url || !this.audio || !this.player) {
            return;
        }

        if (this.currentTrack !== url) {
            this.audio.src = url;
            this.currentTrack = url;
        }

        if (this.trackNameEl) {
            this.trackNameEl.textContent = trackName || 'Unknown Track';
        }

        if (this.artistNameEl) {
            this.artistNameEl.textContent = artistName || 'Unknown Artist';
        }

        if (this.metaEl) {
            const details = [
                meta.album ?? '',
                meta.duration ?? '',
                meta.genre ?? '',
            ].filter(Boolean);

            this.metaEl.textContent = details.join(' • ');
        }

        if (this.artworkEl) {
            if (artworkUrl) {
                this.artworkEl.style.backgroundImage = `url(${artworkUrl})`;
            } else {
                this.artworkEl.style.backgroundImage = 'linear-gradient(135deg, rgba(29,185,84,.4), rgba(255,85,0,.4))';
            }
        }

        this.player.classList.remove('hidden');
        this.audio.play().catch(() => {
            this.togglePlayState(false);
        });
    }
}

const miniPlayerInstance = new MiniPlayer();

window.playPreview = (url, trackName, artistName, artworkUrl, meta = {}) => {
    if (!miniPlayerInstance) {
        return;
    }

    miniPlayerInstance.play({
        url,
        trackName,
        artistName,
        artworkUrl,
        meta,
    });
};
