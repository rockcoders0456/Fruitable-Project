# 🚀 Deployment Checklist

## Pre-Deployment Setup

### 1. **File Organization** ✅
- [ ] Run `php deploy.php` to organize files
- [ ] Verify all files are in correct directories
- [ ] Check that sensitive files are outside web root

### 2. **Database Configuration**
- [ ] Update `app/config/database.php` with production credentials
- [ ] Test database connection
- [ ] Import `app/database_setup.sql` to production database
- [ ] Verify all tables are created correctly

### 3. **Email Configuration**
- [ ] Update `app/config/email.php` with production email settings
- [ ] Test email functionality
- [ ] Configure SMTP credentials (Gmail App Password or other service)

### 4. **Server Configuration**

#### Apache Configuration
- [ ] Set document root to `public` directory
- [ ] Enable mod_rewrite
- [ ] Configure SSL certificate
- [ ] Set up proper file permissions (755 for directories, 644 for files)

#### File Permissions
```bash
# Directories
chmod 755 public/
chmod 755 app/
chmod 755 logs/
chmod 755 backups/

# Files
chmod 644 public/.htaccess
chmod 644 app/config/*.php
```

### 5. **Security Settings**
- [ ] Update all passwords (admin, database, email)
- [ ] Remove or protect development files
- [ ] Configure firewall rules
- [ ] Set up SSL/HTTPS
- [ ] Enable security headers (done in .htaccess)

### 6. **Domain Configuration**
- [ ] Update domain in configuration files
- [ ] Configure DNS settings
- [ ] Set up email domain (if using custom domain)
- [ ] Update contact information

## Post-Deployment Testing

### 7. **Functionality Testing**
- [ ] Test customer registration
- [ ] Test customer login
- [ ] Test product browsing
- [ ] Test shopping cart
- [ ] Test checkout process
- [ ] Test payment methods
- [ ] Test order confirmation emails
- [ ] Test admin panel login
- [ ] Test order management
- [ ] Test product management

### 8. **Performance Testing**
- [ ] Test page load speeds
- [ ] Optimize images
- [ ] Enable caching
- [ ] Test mobile responsiveness
- [ ] Check browser compatibility

### 9. **Security Testing**
- [ ] Test SQL injection protection
- [ ] Test XSS protection
- [ ] Test CSRF protection
- [ ] Verify sensitive files are not accessible
- [ ] Test file upload security

## Production Monitoring

### 10. **Monitoring Setup**
- [ ] Set up error logging
- [ ] Configure backup system
- [ ] Set up uptime monitoring
- [ ] Configure email alerts for errors
- [ ] Set up performance monitoring

### 11. **Backup Strategy**
- [ ] Set up automated database backups
- [ ] Set up file backups
- [ ] Test backup restoration
- [ ] Document backup procedures

## Maintenance

### 12. **Ongoing Maintenance**
- [ ] Regular security updates
- [ ] Database optimization
- [ ] Log rotation
- [ ] Performance monitoring
- [ ] User feedback collection

## Emergency Procedures

### 13. **Disaster Recovery**
- [ ] Document rollback procedures
- [ ] Keep backup of previous version
- [ ] Test restore procedures
- [ ] Document emergency contacts

## Final Checklist

### 14. **Go-Live Verification**
- [ ] All functionality working
- [ ] No error messages
- [ ] Email notifications working
- [ ] Payment processing working
- [ ] Admin panel accessible
- [ ] Mobile responsive
- [ ] SSL certificate valid
- [ ] Performance acceptable
- [ ] Security measures active

---

## 🎯 Quick Deployment Commands

```bash
# 1. Organize files
php deploy.php

# 2. Set permissions (Linux/Mac)
find . -type d -exec chmod 755 {} \;
find . -type f -exec chmod 644 {} \;

# 3. Create backup
mysqldump -u username -p database_name > backup.sql

# 4. Test deployment
curl -I https://yourdomain.com
```

## 📞 Support Contacts

- **Developer**: [Your Contact]
- **Hosting Provider**: [Provider Contact]
- **Domain Registrar**: [Registrar Contact]
- **SSL Certificate**: [Certificate Provider]

---

**Remember**: Always test in a staging environment before deploying to production! 