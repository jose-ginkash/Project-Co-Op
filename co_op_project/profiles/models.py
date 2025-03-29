from django.db import models
from django.contrib.auth.models import User

class Profile(models.Model):
    user = models.OneToOneField(User, on_delete=models.CASCADE)
    phone_number = models.CharField(max_length=15, blank=True, null=True)
    gaming_interests = models.TextField(blank=True, null=True, help_text="List your favorite games or genres.")
    bio = models.TextField(blank=True, null=True)
    avatar = models.ImageField(upload_to='avatars/', blank=True, null=True)
    # Add any additional fields you see fit

    def __str__(self):
        return f"{self.user.username}'s Profile"
